<?php

namespace Benaaacademy\Media\Controllers;

use Benaaacademy\Media\Models\Media;
use Benaaacademy\Platform\Controller;
use Intervention\Image\Facades\Image;

/*
 * Class MediaController
 * @package Benaaacademy\Media\Controllers
 */

class MediaController extends Controller
{

    /*
     * View payload
     * @var array
     */
    public $data = [];


    /*
     * Show all media
     * @param int $page
     * @param string $type
     * @param string $q
     * @return mixed
     */
    function index($page = 1, $type = "all", $q = "")
    {

        $limit = 60;
        $offset = ($page - 1) * $limit;

        $query = Media::orderBy("updated_at", "DESC");

        if ($type != "all") {
            if ($type == "application") {
                $query->whereIn("type", ["text", "application"]);
            } else {
                $query->where("type", $type);
            }
        }

        if ($q != "") {
            $query->search(urldecode($q));
        }


        if (request()->filled("id")) {
            $query->where("id", "=", request()->get("id"));
        }

        $files = $query->limit($limit)->skip($offset)->get();

        $new_files = array();
        foreach ($files as $file) {
            $new_files[] = $file->response($file);
        }

        $this->data["files"] = $new_files;

        $this->data["q"] = $q;
        $this->data["page"] = $page;

        return view()->make("media::index", $this->data);
    }


    /*
     * Save links to media
     * @accept youtube, soundcloud and direct static links
     * @return mixed
     */
    function link()
    {

        if ($link = request()->get("link")) {

            $media = new Media();

            if (strstr($link, "youtube.") and get_youtube_video_id($link)) {
                $response = $media->saveYoutube($link)->response();
            } else if (strstr($link, "soundcloud.")) {
                $response = $media->saveSoundcloud($link)->response();
            } else {
                $response = $media->saveLink($link)->response();
            }

            $response->html = view()->make("media::index", $response->media)->render();

            return response()->json($response, 200);
        }
    }

    /*
     * Download media
     * @return string
     */
    function download()
    {

        if (in_array(request()->get("path"), [null, []])) {
            return response()->json([]);
        }

        $path = request()->get("path");

        $sizes = [];

        // Adding original size

        $size = (object)[];

        $size->name = "original";
        $size->path = $path;
        $size->url = uploads_url($path);
        $size->width = NULL;
        $size->height = NULL;

        $sizes[] = $size;

        foreach (config("media.sizes", []) as $name => $dimensions) {

            $size = (object)[];

            list($year, $month, $filename) = @explode("/", $path);

            $size->name = $name;
            $size->path = $year . "/" . $month . "/" . $name . "-" . $filename;
            $size->url = thumbnail($path, $name);
            $size->width = $dimensions[0];
            $size->height = $dimensions[1];

            $sizes[] = $size;
        }

        return json_encode($sizes);
    }

    /*
     * Crop images
     * @return void
     */
    function crop()
    {
        $path = request("path");
        $size = request("size");

        $w = (int) request("w");
        $h = (int) request("h");
        $x = (int) request("x");
        $y = (int) request("y");

        list($year, $month, $filename) = @explode("/", $path);

        $src = uploads_path($year . "/" . $month . "/" . $size . "-" . $filename);

        if ($w != "") {

            $img = Image::make(uploads_path($path));

            $img->crop($w, $h, $x, $y)->save($src);

            $sizes = config("media.sizes");

            $current_size = $sizes[$size];

            return response()->json([
                "url" => uploads_url($year . "/" . $month . "/" . $size . "-" . $filename),
                "path" => $year . "/" . $month . "/" . $size . "-" . $filename,
                "width" => $current_size[0],
                "height" => $current_size[1],
                "status" => 1,
            ]);
        }
    }

    /*
     * Upload files from local computer
     * @return mixed
     */
    public function upload()
    {

        $file = request()->file('files')[0];

        $media = new Media();

        $validator = $media->validateFile($file);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return response()->json(array(
                "error" => join("<br />", str_replace("files.0", $file->getClientOriginalName(), $errors["files.0"]))
            ), 200);
        }

        $id = $media->saveFile($file);

        $media = Media::where("id", $id)->first();

        $row = (object)[];
        $row->id = $id;
        $row->name = $media->path;
        $row->title = $media->title;
        $row->size = "";
        $row->url = uploads_url($media->path);
        $row->thumbnail = thumbnail($media->path);
        $row->html = view()->make("media::index", array(
            "files" => array(0 => (object)array(
                "id" => $media->id,
                "provider" => "",
                "provider_id" => "",
                "type" => $media->type,
                "url" => uploads_url($media->path),
                "thumbnail" => thumbnail($media->path),
                "size" => "",
                "path" => $media->path,
                "duration" => "",
                "title" => $media->title,
                "description" => "",
                "created_at" => $media->created_at
            ))
        ))->render();

        return response()->json(array('files' => array($row)), 200);
    }

    /*
     * Delete media
     * @return void
     */
    public function save()
    {

        if (request()->isMethod("post")) {
            $media = Media::find(request()->get("file_id"));
            $media->title = request()->get("file_title");
            $media->description = request()->get("file_description");
            $media->save();
        }
    }

    /*
     * Delete media
     * @return void
     */
    public function delete()
    {

        if (request()->isMethod("post")) {

            $media = Media::find(request()->get("id"));

            $media->delete();

            if ($media->provider == NULL or $media->provider == "") {

                if (file_exists(uploads_path($media->path))) {
                    @unlink(uploads_path($media->path));
                }

                $parts = explode(".", $media->path);

                $extension = end($parts);

                if (in_array(strtolower($extension), array("jpg", "jpeg", "gif", "png", "bmp"))) {

                    $sizes = config("media.sizes");

                    foreach ($sizes as $size => $dimensions) {

                        $dir_parts = explode("/", $media->path);
                        $file = $dir_parts[0] . "/" . $dir_parts[1] . "/" . $size . "-" . $dir_parts[2];

                        @unlink(uploads_path($file));
                    }
                }
            }
        }
    }
}
