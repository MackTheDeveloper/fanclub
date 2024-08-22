<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\API\V1\FanAPIController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfilePhoto;
use Illuminate\Http\Request;
use App\Models\Artist;
use App\Http\Controllers\API\V1\ArtistAPIController;
use App\Http\Controllers\API\V1\SongAPIController;
use App\Models\MusicGenres;
use App\Models\Songs;
use App\Http\Responses\S3FileStream;
use App\Models\Fan;
use App\Models\FanPlaylist;
use App\Models\FanPlaylistSongs;
use App\Models\MusicCategories;
use App\Models\SongVariants;
use App\Models\TmpSongs;
use Exception;
use Validator;
use Session;
use Response;
use URL;
use Storage;
use Auth;
use Illuminate\Support\Facades\File;
use Mail;

class SongsFrontController extends Controller
{
	public function add()
	{
		$musicGenres = MusicGenres::where('status', '1')->pluck('name', 'id');
		$musicCategories = MusicCategories::where('status', '1')->pluck('name', 'id');
		return view('frontend.songs.upload', compact('musicGenres', 'musicCategories'));
	}

	public function upload(Request $request)
	{
		$api = new SongAPIController();
		$data = $api->SongCreate($request);
		$data = $data->getData();
		//$content = $data->component;
		// if ($data->statusCode == 200) {
		// 	$notification = array(
		// 		'message' => $data->message,
		// 		'alert-type' => 'success'
		// 	);
		// 	return redirect()->route('SongUploadView')->with($notification);
		// }
		return Response::json($data);
	}

	public function myMusic()
	{
		$api = new SongAPIController();
		$data = $api->myMusic();
		$data = $data->getData();
		$content = $data->component;
		// pre($content);
		if ($data->statusCode == 200) {
			$content = componentWithNameObject($content);
			return view('frontend.songs.my-music', compact('content'));
		} else {
			if ($data->statusCode == 300) {
				return redirect('/home')->withErrors($content)->withInput();
			}
		}
	}

	public function getSongAccess($slug, $resolution = "")
	{
		$file = "";
		$songData = Songs::where('slug', $slug)->first();
		if ($songData) {
			$sendFile = $songData->file;
			if ($resolution) {
				$resData = [];
				if ($resolution == 'mp3') {
					$type = "mp3";
					$resData = SongVariants::where('song_id', $songData->id)->where('type', $type)->first();
					if ($resData && isset($resData->url)) {
						$sendFile = $resData->url;
					}
				} else {
					$type = getSupportedMime(getBrowser());
					// $type = "mp4";
					$resData = SongVariants::where('song_id', $songData->id)->where('resolution', $resolution)->where('type', $type)->first();
					if ($resData && isset($resData->url)) {
						$sendFile = $resData->url;
					}
				}
			}
			$file = TmpSongs::getFileNameByUrl($sendFile);
			// $fileName = $songData->name;
			$filestream = new S3FileStream($file);
			return $filestream->output();
		} else {
			abort(404, 'Page not found');
		}
	}

	public function getSongDownload($slug, $resolution = "")
	{
		$file = "";
		$songData = Songs::where('slug', $slug)->first();
		$resolution = SongVariants::getMaxResultion($songData->id);
		if ($songData) {
			$type = 'mp4';
			$sendFile = $songData->file;
			if ($resolution) {
				$resData = [];
				if ($resolution == 'mp3') {
					$type = "mp3";
					$resData = SongVariants::where('song_id', $songData->id)->where('type', $type)->first();
					if ($resData && isset($resData->url)) {
						$sendFile = $resData->url;
					}
				} else {
					$type = "mp4";
					$resData = SongVariants::where('song_id', $songData->id)->where('resolution', $resolution)->where('type', $type)->first();
					if ($resData && isset($resData->url)) {
						$sendFile = $resData->url;
					}
				}
			}

			$file = TmpSongs::getFileNameByUrl($sendFile);
			$fileName = $songData->name;
			$headers = [
				'Content-Type'        => 'application/' . $type,
				'Content-Disposition' => 'attachment; filename="' . $fileName . '.' . $type . '"',
				//'Content-Disposition' => 'attachment; filename="' . $fileName . '_' . $resolution . '.'.$type.'"',
			];
			return \Response::make(Storage::disk('s3')->get($file), 200, $headers);
		} else {
			abort(404, 'Page not found');
		}
	}

	/* public function getSongDownloadAll()
	{
		$slug = 'hollywood';
		$authId = User::getLoggedInId();
		$playlistData = FanPlaylist::where('slug', $slug)->where('user_id', $authId)->first();
		if ($playlistData) {
			$playlistId = $playlistData->id;
			$allSongsData = FanPlaylistSongs::getFanSongsForMusicPlayer($playlistId, '');
			//pre($allSongsData);



			$zipname = public_path('/assets/d-zip/tmp-z-' . rand() . '-' . time() . '.zip');
			$zip = new \ZipArchive;
			$zip->open($zipname, \ZipArchive::CREATE);

			$disk = Storage::disk('s3');
			foreach ($allSongsData as $filename) {
				//$file = file_get_contents($filename['s3VideoUrl']);
				//$zip->addFromString(basename($filename['songName']). '.mp4', $file);

				$baseName = basename($filename['songIcon']);
				$fileName = public_path('assets/images/album/') . basename($filename['songIcon']);
				if (file_exists($fileName)) {
					$zip->addFile($fileName, $baseName);
				}
			}

			$zip->close();
			//unlink($zipname);
			$path = $zipname;
			return response()->download($path);
			exit;
		}
	} */

	public function allSongs(Request $request, $search = "")
	{
		$api = new SongAPIController();
		//patch by nivedita for search page see all//
		$data = $api->allSongs($search);
		$data = $data->getData();
		$content = $data->component;
		// pre($content);
		$title = 'All Songs';
		return view('frontend.songs.all-songs', compact('content', 'title'));
	}
	public function loadmore(Request $request, $search = "")
	{
		$page = $request->page;
		$api = new SongAPIController();
		$data = $api->allSongs($search, $page);
		$data = $data->getData();
		$content = $data->component;
		return view('frontend.songs.load-more-songs', compact('content'));
	}

	public function songsIncreaseView(Request $request)
	{
		$songData = Songs::where('slug', $request->slug)->first();
		if ($songData) {
			$request->merge(['song_id' => $songData->id]);
			$api = new SongAPIController();
			$data = $api->SongsIncreaseView($request);
			$data = $data->getData();
			return Response::json($data);
		}
	}

	public function SongsIncreaseStream(Request $request)
	{
		$songData = Songs::where('slug', $request->slug)->first();
		if ($songData) {
			$request->merge(['song_id' => $songData->id]);
			$api = new SongAPIController();
			$data = $api->SongsIncreaseStream($request);
			$data = $data->getData();
			return Response::json($data);
		}
	}
	
	public function SongsAddToRecent(Request $request)
	{
		$songData = Songs::where('slug', $request->slug)->first();
		if ($songData) {
			$request->merge(['song_id' => $songData->id]);
			$api = new SongAPIController();
			$data = $api->SongsAddToRecent($request);
			$data = $data->getData();
			return Response::json($data);
		}
	}

	public function edit($id)
	{
		$api = new SongAPIController();
		$data = $api->SongEdit($id);
		$data = $data->getData();
		$content = $data->component;
		// app('request')->create(URL::previous())->getName();
		if (url()->current()!= url()->previous()) {
			$url = url()->previous();
			Session::put('song_update_previous_url', $url);
		}
		// pre($route);
		$musicGenres = MusicGenres::where('status', '1')->pluck('name', 'id');
		$musicCategories = MusicCategories::where('status', '1')->pluck('name', 'id');
		return view('frontend.songs.edit', compact('musicGenres', 'musicCategories', 'content'));
	}
	public function update(Request $request)
	{
		$api = new SongAPIController();
		$data = $api->SongUpdate($request);
		$data = $data->getData();
		// pre($data);
		$notification = array(
			'message' => $data->message,
			'alert-type' => 'success'
		);
		if (Session::has('song_update_previous_url')) {
			$url = Session::get('song_update_previous_url');
			return redirect()->to($url)->with($notification);
		}else{
			return redirect()->route('ArtistDashboard')->with($notification);
		}
	}
}
