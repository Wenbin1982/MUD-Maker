<?php

namespace App\Http\Controllers;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Domain;
use App\MudFile;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class JsonController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => $this->responseDataUsers(Auth::user())['data'],
            'deleteFiles' => $this->responseDataUsers(Auth::user())['deleteFiles'],
            'url' => URL::to('/')
        ]);
    }

    public function createShow() {
        $data = [];
        $data['url'] = URL::to('/');
        $data['name_mud_file'] =  Carbon::now()->getTimestamp();
        $data['manufacturer'] = Domain::where('user_id', Auth::id())->first()->company;
        return response(['data' => $data]);
    }

    private function responseDataUsers($authUser)
    {
        $deleteFiles = [];
        $data = [];

        $selectUserCompany = MudFile::popular($this->filterUserCompany($authUser))->get()->toArray();
        $active_user_id = array_column($selectUserCompany, 'user_id');
        $nameUsers = User::whereIn('id', $active_user_id)->get();

        foreach (MudFile::whereIn('user_id', $active_user_id)->get()->toArray() as $item) {
            foreach ($nameUsers->toArray() as $value) {
                if ($value['id'] === $item['user_id']) {
                    $item['last_update_name'] = User::find($item['last_update_user_id'])->getAttributes()['name'];
                    $item['name'] = $value['name'];
                    array_push($data, $item);
                }
            }
        }

        foreach (MudFile::onlyTrashed()->where('domain', $this->filterUserCompany(Auth::user()))->get()->toArray() as $item) {
            $item['name'] = User::find($item['user_id'])['name'];
            array_push($deleteFiles, $item);
        }

        return ['data' => $data, 'deleteFiles' => $deleteFiles];
    }

    private function filterUserCompany($authUser)
    {
        $userCompany = explode("@", $authUser->toArray()['email']);
        $domain = Domain::where('domain', array_pop($userCompany))->get()->toArray();

        if (count($domain)) {
            $selectDomain = $domain[0];
            return $selectDomain['domain'];
        }

        return 'no company';
    }

    private function generateP7sFile($mudFileName, $firstArraySplitJsonName) {
        $in = storage_path("app/json_files/$mudFileName");
        $out = storage_path("app/json_files/$firstArraySplitJsonName.p7s");
        $cert = storage_path('cert.pem');
        $key = storage_path('key.pem');
        $command = "openssl cms -sign -signer $cert -inkey $key -in $in -binary -outform DER -out $out";

        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        echo $process->getOutput();
    }

    public function store(Request $request)
    {
        $json = new MudFile();
        $json->user_id = Auth::id();
        $json->last_update_user_id = Auth::id();
        $json->domain = $this->filterUserCompany(Auth::user());

        //file name
        $mudFileName = $request->jsonData['files']['mudFileNameJson'];
        $mudFilePath = $request->jsonData['files']['mudFilePathJson'];
        $pathToJsonFiles = 'json_files/';

        Storage::put($pathToJsonFiles . $mudFileName, $request->jsonData['json']);
        $splitJsonName = explode('.', $mudFileName);
        $firstArraySplitJsonName = array_shift($splitJsonName);
        $json->nameMudFile = $mudFileName;
        $json->path = $mudFilePath;
        $json->link_mud_file = $mudFileName;
        $json->model = $request->jsonData['files']['model'];
        $json->manufacturer = $request->jsonData['files']['manufacturer'];
        $json->software = $request->jsonData['files']['software'];
        $json->deviceType = $request->jsonData['files']['deviceType'];
        $json->deviceSelect = $request->jsonData['files']['deviceSelect'];
        $json->save();

        $arr = ['controllerAccess', 'controllersSpecific', 'device', 'internetCommunication', 'localCommunication', 'specificType'];
        $mudFile = $request->jsonData['files']['mudFile'];
        foreach ($arr as $item) {
            if (!empty($mudFile[$item])) {
                $baseClass = "App\\" . ucfirst($item);

                foreach ($mudFile[$item] as $value) {
                    $model = new $baseClass();
                    $model->name = $value['name']['value'];
                    $model->portl = $value['portl']['value'];
                    $model->port = $value['port']['value'];
                    $model->initiatedBy = $value['initiatedBy']['selected']['value'];
                    $model->protocolSelect = $value['protocolSelect']['selected']['value'];
                    $model->json_id = $json->id;
                    $model->save();
                }
            }
        }
        /* encript in the p7s format start */
        $this->generateP7sFile($mudFileName, $firstArraySplitJsonName);
        /* encript in the p7s format end */

        return response()->json($json->id);
    }

    public function edit(Request $request, $edit)
    {

        $mudFiles = MudFile::find($edit);

        $arr = ['controllerAccess', 'controllersSpecific', 'device', 'internetCommunication', 'localCommunication', 'specificType'];
        $data = [];
        foreach ($arr as $item) {
            $data[$item] = $mudFiles[$item]->toArray();
        }
        $data['manufacturer'] = $mudFiles->manufacturer;
        $data['model'] = $mudFiles->model;
        $data['deviceType'] = $mudFiles->deviceType;
        $data['software'] = $mudFiles->software;
        $data['deviceSelect'] = $mudFiles->deviceSelect;
        $data['path'] = $mudFiles->path;
        $data['nameMudFile'] = $mudFiles->nameMudFile;
        $data['url'] = URL::to('/');

        return $data;
    }

    public function update(Request $request)
    {

        $json = MudFile::find($request->jsonId['id']);

        $file = $json->nameMudFile;
        Storage::delete('json_files/' . $file);
        $json->last_update_user_id = Auth::id();
        Storage::put('json_files/' . $file, $request->jsonData['json']);
        $json->nameMudFile = $file;
        $json->deviceSelect = $request->jsonData['files']['deviceSelect'];
        $json->update();

        $arr = ['internetCommunication', 'controllerAccess', 'controllersSpecific', 'device', 'localCommunication', 'specificType'];
        $mudFile = $request->jsonData['files']['mudFile'];
        foreach ($arr as $item) {
            $baseClass = "App\\" . ucfirst($item);
            $model = $baseClass::where('json_id', $request->jsonId['id'])->get();

            if (!empty($mudFile[$item])) {
                foreach ($mudFile[$item] as $key => $value) {
                    if (!empty($model[$key])) {
                        $this->modelColumn($model[$key], $value, $json);
                        $model[$key]->update();
                    } else {
                        $model = new $baseClass();
                        $this->modelColumn($model, $value, $json);
                        $model->save();
                    }
                }
            } else {
                if (count($model)) {
                    foreach ($model as $key => $collection) {
                        $collection->delete();
                    }

                }
            }

        }

        return response()->json($json->id);
    }

    static function modelColumn($model, $value, $json)
    {
        $model->name = $value['name']['value'];
        $model->portl = $value['portl']['value'];
        $model->port = $value['port']['value'];
        $model->initiatedBy = $value['initiatedBy']['selected']['value'];
        $model->protocolSelect = $value['protocolSelect']['selected']['value'];
        $model->json_id = $json->id;
    }

    public function downloadJson(Request $request)
    {
        return response()->download(storage_path('app/json_files/' . MudFile::find($request->jsonId)->nameMudFile));
    }

    public function show(Request $request)
    {

        $file = MudFile::where('id', $request->jsonId)->first();

        $json = Storage::get('/json_files/' . $file->nameMudFile);
        $file->link_mud_file = URL::to('/') . '/mudFile/' . $file->link_mud_file;
        return response(['json' => $json, 'name' => $file->nameMudFile, 'link_file' => URL::to('/') . '/mudFile/'. $file->path]);
    }

    public function delete(Request $request, $id)
    {
        $file = MudFile::find($id);
        $file->delete_name_user = Auth::user()->getAttributes()['name'];
        $file->update();
        $file->delete();

        return response()->json([
            'data' => $this->responseDataUsers(Auth::user())['data'],
            'deleteFiles' => $this->responseDataUsers(Auth::user())['deleteFiles']
        ]);
    }

    public function restore(Request $request)
    {
        foreach ($request->id as $id) {
            MudFile::withTrashed()->where('id', $id)->restore();
        }

        return response()->json([
            'data' => $this->responseDataUsers(Auth::user())['data'],
            'deleteFiles' => $this->responseDataUsers(Auth::user())['deleteFiles']
        ]);
    }

    public function myLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $request->session()->token();
    }

    public function linkMudFile($manufacturer_id, $device_id, $model_id, $version_id, $name)
    {
        $file = storage_path() . '/app/json_files/' . $name;
        if (file_exists($file)) {
            return response(file_get_contents($file));
        } else {
            exit('Requested file does not exist on our server!');
        }
    }

    public function apiToken()
    {
        if (Auth::check()) {
            return User::find(Auth::id())->api_token;
        }
        return null;
    }

    public function viewAdminMudFiles()
    {
        return view('admin.index');
    }

    public function showAdminMudFiles()
    {
        return response()->json(MudFile::with('user')->get());
    }

    public function cloneMudFile($id)
    {
        $file = MudFile::find($id);
        $name = $file->model . '_' . $file->manufacturer . '_' . $file->software . '_' . $file->deviceType;
        $nameJson = Carbon::now()->getTimestamp() . '_' . $name;
        $fileName = $nameJson . '.json';
        Storage::copy('json_files/' . $file->nameMudFile, 'json_files/' . $fileName);
        $cloneFile = $file->replicate();
        $cloneFile->nameMudFile = $fileName;
        $cloneFile->save();

        /* encript in the p7s format start */
        $this->generateP7sFile($fileName, $nameJson);
        /* encript in the p7s format end */

        $arrayBelongToMudFile = [
            'internetCommunication',
            'controllerAccess',
            'controllersSpecific',
            'device',
            'localCommunication',
            'specificType'
        ];

        foreach ($arrayBelongToMudFile as $item) {
            $this->replicateMudFile($file->$item, $cloneFile->$item());
        }

        return response()->json([
            'data' => $this->responseDataUsers(Auth::user())['data'],
            'deleteFiles' => $this->responseDataUsers(Auth::user())['deleteFiles']
        ]);
    }

    private function replicateMudFile($name, $cloneFile)
    {
        foreach ($name as $item) {
            $replicate = $item->replicate();
            $cloneFile->save($replicate);
        }
    }
}
