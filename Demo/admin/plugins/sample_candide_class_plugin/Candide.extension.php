<?php
switch ($_GET["extension_type"]) {
    case Basic::TYPE_ITEM:
        // UserInteractive getter function
        $this->addCallable(
            "youtube_video",
            function (String $title, Bool $allowfullscreen = true) {
                $this->getElement($title,"youtube_video",[
                    "allowfullscreen"=>$allowfullscreen,
                    "plugin"=> "sample_candide_class_plugin"
                    ]);
            }
        );
        // Formatter to display data properly
        $this->addCallable(
            "formatyoutube_videoElement",
            function(Array $element):String {
                $allowfullscreen = ($element["allowfullscreen"] ?? true) ? "allowfullscreen" : "";
                return '<iframe class="candide_youtube_video" src="https://www.youtube.com/embed/'.$element["data"].'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" '.$allowfullscreen.'></iframe>';
            }
        );
        break;
    case Basic::TYPE_FIELDS_GENERATOR:
        // Field generator for admin side
        $this->addCallable(
            "getyoutube_videoInput",
            function(String $name, String $data, Array $fieldInfos):String{
                return '<input class="youtube_video_input" type="text" placeholder="Url" name="'.$name.'" value="'.$data.'">';
            }
        );
        break;
}