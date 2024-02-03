<?php

use Illuminate\Support\Facades\File;

/**
 * @param string $file
 * @return string
 */
function uploads_path($file = "")
{
    return UPLOADS_PATH . "/" . $file;
}



if (!function_exists("uploads_url")) {

    function uploads_url($file = "")
    {
        $media_url = config("media.drivers.local.url");

        if (!$media_url) {
            $media_url = url("uploads");
        }

        return $media_url . "/" . $file;
    }
}


if (!function_exists("thumbnail")) {

    function thumbnail($file = "", $size = "thumbnail", $default = "files/file.png")
    {
        $parts = explode(".", $file);
        $ext = end($parts);

        $parts = explode("/", $file);

        if (in_array(strtolower($ext), array("jpg", "jpeg", "png", "bmp", "gif"))) {
            $file_path = $parts[0] . "/" . $parts[1] . "/" . $size . "-" . $parts[2];
            return uploads_url($file_path);
        }

        if (File::exists(public_path("plugins/admin/files/" . strtolower($ext) . ".png"))) {
            return assets("admin::files/" . strtolower($ext) . ".png");
        } else {
            return assets($default);
        }
    }
}

if (!function_exists("glob_recursive")) {

    function glob_recursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, glob_recursive($dir . '/' . basename($pattern), $flags));
        }
        return $files;
    }
}

if (!function_exists("rrmdir")) {

    function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir")
                        rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}


if (!function_exists("get_youtube_video_id")) {

    function get_youtube_video_id($url)
    {

        if (strstr($url, "/v/")) {
            $array = parse_url($url);
            $path = trim($array["path"], "/");
            if ($parts = @explode("/", $path)) {
                if (isset($parts[1])) {
                    return $parts[1];
                }
            }
        }
        $url_string = parse_url($url, PHP_URL_QUERY);
        parse_str($url_string, $args);
        return isset($args['v']) ? $args['v'] : false;
    }
}


if (!function_exists("get_extension")) {

    function get_extension($mime = "")
    {

        $types = config("media.mimes");

        foreach ($types as $extension => $mimes) {
            if ((is_array($mimes) and in_array($mime, $mimes)) or (!is_array($mimes) and $mime == $mimes)) {
                return $extension;
            }
        }

        return false;
    }
}


if (!function_exists("get_youtube_video_details")) {

    function get_youtube_video_details($id)
    {

        $row = new stdClass();

        $row->title = "";
        $row->description = "";
        $row->keywords = "";
        $row->length = "";
        $row->image = "";
        $row->embed = "";

        $json_data = file_get_contents("https://www.googleapis.com/youtube/v3/videos?id=" . $id . "&part=snippet,contentDetails&key=AIzaSyCth5RwV69-KUdPR9DM3_rxUt--kE4bmLU");
        $data = json_decode($json_data, true);
        if (isset($data["items"][0])) {

            $item = $data["items"][0];
            $row->title = $item["snippet"]["title"];
            $row->description = $item["snippet"]["description"];
            $row->keywords = "";
            $row->length = youtube_video_length($item["contentDetails"]["duration"]);
            $row->image = "https://i.ytimg.com/vi/" . $id . "/0.jpg";
            $row->embed = "https://www.youtube.com/embed/" . $id;
        }

        return $row;
    }
}


if (!function_exists("youtube_video_length")) {

    function youtube_video_length($youtube_time)
    {
        preg_match_all('/(\d+)/', $youtube_time, $parts);
        $hours = floor($parts[0][0] / 60);
        $minutes = $parts[0][0] % 60;
        if (isset($parts[0][1]))
            $seconds = $parts[0][1];
        else
            $seconds = $parts[0][0];

        return $hours * 60 * 60 + $minutes * 60 + $seconds;
    }
}


if (!function_exists("get_soundcloud_track_details")) {

    function get_soundcloud_track_details($url = "")
    {

        $url = "http://api.soundcloud.com/resolve.json?url=" . $url . "&client_id=203ed54b9fd03054e1aa2b2cae337eae";

        dump($url);
        if ($content = @file_get_contents($url)) {
            $data = json_decode($content);
            $row = new stdClass();
            $row->id = $data->id;
            $row->title = @$data->title;
            $row->description = $data->description;
            $row->length = round(@$data->duration / 1000);
            $row->link = $data->permalink_url;
            if (@$data->artwork_url != null) {
                $row->image = $data->artwork_url;
            } else {
                $row->image = "";
            }

            return $row;
        }
    }
}


if (!function_exists("format_duration")) {

    function format_duration($init)
    {
        if(!is_numeric($init)) {
            return $init;
        }
        
        $init = (int) $init;
        
        $hours = floor($init / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;
        $string = "";
        if ($hours != 0) {
            $string .= $hours . ":";
        }

        if (strlen($hours) == 1) {
            $hours = "0" . $hours;
        }
        if (strlen($minutes) == 1) {
            $minutes = "0" . $minutes;
        }
        if (strlen($seconds) == 1) {
            $seconds = "0" . $seconds;
        }

        $string .= "$minutes:$seconds";
        return $string;
    }
}


if (!function_exists("format_file_size")) {

    function format_file_size($size, $type = "KB")
    {
        switch ($type) {
            case "KB":
                $filesize = $size * .0009765625; // bytes to KB
                break;
            case "MB":
                $filesize = ($size * .0009765625) * .0009765625; // bytes to MB
                break;
            case "GB":
                $filesize = (($size * .0009765625) * .0009765625) * .0009765625; // bytes to GB
                break;
        }

        if ($filesize < 0) {
            return $filesize = 'unknown file size';
        } else {
            return round($filesize, 2) . ' ' . $type;
        }
    }
}
