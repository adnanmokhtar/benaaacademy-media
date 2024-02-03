<div class="row">

    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">
                            <label
                                for="allowed_file_types">{{ trans("media::options.attributes.allowed_file_types") }}</label>
                            <br/>
                            <input type="hidden" name="option[media_allowed_file_types]" id="allowed_file_types"
                                   required="required"
                                   value="{{ Request::old('option.media_allowed_file_types', option('media_allowed_file_types')) }}">
                            <ul id="mytags"></ul>
                        </div>

                        <div class="form-group">
                            <label
                                for="max_file_size">{{ trans("media::options.attributes.max_file_size") }}</label>
                            <div class="input-group m-b">
                                <input name="option[media_max_file_size]" type="number" step="1" min="1"
                                       value="{{ Request::old('option.media_max_file_size', option('media_max_file_size')) }}"
                                       class="form-control col-md-11" id="max_file_size"
                                       required="required"
                                       placeholder="{{ trans("media::options.attributes.max_file_size") }}">
                                <span
                                    class="input-group-addon">{{ trans("media::options.kilobytes") }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label
                                for="max_width">{{ trans("media::options.attributes.max_width") }}</label>
                            <div class="input-group m-b">
                                <input name="option[media_max_width]" required="required" type="number" step="1" min="1"
                                       value="{{ Request::old('option.media_max_width', option('media_max_width')) }}"
                                       class="form-control col-md-11" id="max_width"
                                       placeholder="{{ trans("media::options.attributes.max_width") }}">
                                <span class="input-group-addon">{{ trans("media::options.pixels") }}</span>
                            </div>
                        </div>


                    </div>

                    <div class="col-md-6">


                        <!-- <div class="form-group switch-row">
                            <label class="col-sm-10 control-label"
                                   for="media_thumbnails">{{ trans("media::options.attributes.media_thumbnails")
                                }}</label>
                            <div class="col-sm-2">
                                <input @if (option("media_thumbnails", 0)) checked="checked" @endif
                                type="checkbox" value="1" class="switcher switcher-sm thumbnail_check"/>
                                <input type="hidden" name="option[media_thumbnails]"
                                       value="{{ option("media_thumbnails", 0) }}">
                            </div>
                        </div> -->

                        <br/>

                        <div class="form-group">
                            <label
                                for="media_resize_mode">{{ trans("media::options.attributes.resize_mode")
                                }}</label>
                            <select id="media_resize_mode" class="form-control chosen-select chosen-rtl"
                                    name="option[media_resize_mode]">
                                @foreach (["resize", "resize_crop", "color_background", "gradient_background", "blur_background"] as $mode)
                                    <option value="{{ $mode }}"
                                            @if (option("media_resize_mode") == $mode) selected="selected" @endif>
                                        {{ trans("media::options.resize_mode_" . $mode) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group color-background-area" style="display: none">
                            <label
                                for="resize_background_color">{{
                                trans("media::options.attributes.resize_background_color") }}</label>
                            <input name="option[media_resize_background_color]" type="text"
                                   value="{{ Request::old("option.media_resize_background_color", option("media_resize_background_color")) }}"
                                   class="form-control color-input" id="resize_background_color"
                                   placeholder="{{ trans("media::options.attributes.resize_background_color") }}">
                        </div>

                        <div class="row gradient-background-area" style="display: none">

                            <div class="form-group col-md-6">
                                <label
                                    for="resize_gradient_first_color">{{
                                    trans("media::options.attributes.resize_gradient_first_color") }}</label>
                                <input name="option[media_resize_gradient_first_color]" type="text"
                                       value="{{ Request::old('option.media_resize_gradient_first_color', option('media_resize_gradient_first_color')) }}"
                                       class="form-control color-input" id="resize_gradient_first_color"
                                       placeholder="{{ trans("media::options.attributes.resize_gradient_first_color") }}">
                            </div>

                            <div class="form-group col-md-6">
                                <label
                                    for="resize_gradient_second_color">{{ trans("media::options.attributes.resize_gradient_second_color") }}</label>
                                <input name="option[media_resize_gradient_second_color]" type="text"
                                       value="{{ Request::old('option.media_resize_gradient_second_color', option('media_resize_gradient_second_color')) }}"
                                       class="form-control color-input"
                                       id="resize_gradient_second_color"
                                       placeholder="{{ trans("media::options.attributes.resize_gradient_second_color") }}">
                            </div>

                        </div>


                    </div>

                <!--

                    <div class="col-md-6" style="display: none">

                        <fieldset>
                            <legend>{{ trans("media::options.amazon_integration") }}</legend>

                            <div class="form-group switch-row">
                                <label class="col-sm-10 control-label"
                                       for="s3_status">{{ trans("media::options.attributes.s3_status") }}</label>
                                <div class="col-sm-2">
                                    <input @if (option("media.s3.status")) checked="checked" @endif
                    type="checkbox" id="s3_status" name="s3_status" value="1"
                           class="switcher switcher-sm">
                </div>
            </div>

            <div class="form-group">

                <label for="s3_bucket"
                       col-sm-10>{{ trans("media::options.attributes.s3_bucket") }}</label>

                                <input name="s3_bucket" type="text"
                                       value="{{ @Request::old(" s3_bucket", option("media.s3.bucket")) }}"
                                       class="form-control" id="s3_bucket"
                                       placeholder="{{ trans("media::options.attributes.s3_bucket") }}">
                            </div>

                            <div class="form-group">
                                <label for="s3_region"
                                       col-sm-10>{{ trans("media::options.attributes.s3_region") }}</label>
                                <input name="s3_region" type="text"
                                       value="{{ @Request::old(" s3_region", option("media.s3.region")) }}"
                                       class="form-control" id="s3_region"
                                       placeholder="{{ trans("media::options.attributes.s3_region") }}">
                            </div>

                            <div class="form-group switch-row">
                                <label class="col-sm-10 control-label"
                                       for="s3_delete_locally">{{ trans("media::options.attributes.s3_delete_locally")
                                    }}</label>
                                <div class="col-sm-2">
                                    <input
                                            @if (option("media.s3.delete_locally")) checked="checked" @endif
                    type="checkbox" id="s3_delete_locally" name="s3_delete_locally"
                            value="1"
                            class="switcher switcher-sm">
                </div>
            </div>

        </fieldset>
    </div>

-->
                </div>

            </div>

        </div>
    </div>
</div>


@section("head")

    <link href="{{ assets('admin::tagit/jquery.tagit.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ assets('admin::tagit/tagit.ui-zendesk.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ assets('admin::css/plugins/colorpicker/bootstrap-colorpicker.min.css') }}" rel="stylesheet"/>

    <style>

        .colorpicker {
            right: unset;
        }

    </style>

@stop

@section("footer")

    <script src="{{ assets('admin::tagit/tag-it.js') }}" type="text/javascript"></script>
    <script src="{{ assets('admin::js/plugins/colorpicker/bootstrap-colorpicker.min.js') }}"></script>

    <script>

        $(document).ready(function () {


            function switch_mode(mode) {

                $(".gradient-background-area").hide();
                $(".color-background-area").hide();

                if (mode == "color_background") {
                    $(".color-background-area").show();
                }

                if (mode == "gradient_background") {
                    $(".gradient-background-area").show();
                }

            }

            switch_mode($("[name=option\\[media_resize_mode\\]]").val());

            $("[name=option\\[media_resize_mode\\]]").change(function () {
                var base = $(this);
                var mode = base.val();
                switch_mode(mode);
            });

            $('.color-input').colorpicker();

            $("#change_logo").filemanager({
                types: "image",
                done: function (result, base) {
                    if (result.length) {
                        var file = result[0];
                        $("#site_logo_path").val(file.media_path);
                        $("#site_logo").attr("src", file.media_thumbnail);
                    }
                },
                error: function (media_path) {
                    alert(media_path + " {{ trans('media::options.file_not_supported') }}");
                }
            });

            $("#mytags").tagit({
                singleField: true,
                singleFieldNode: $('#allowed_file_types'),
                allowSpaces: true,
                minLength: 2,
                placeholderText: "",
                removeConfirmation: true,
                availableTags: ['jpg', 'png', 'jpeg', 'gif', 'doc', 'docx', 'txt', 'pdf', 'zip']
            });

        });

    </script>

@stop
