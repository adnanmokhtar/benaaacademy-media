@foreach ($files as $row)
    <div class="dz-preview dz-image-preview" media-id="{{ $row->id }}">

        @if ($row->provider == "youtube")
            <input type="hidden" name="provider" value="youtube"/>
            <input type="hidden" name="duration" value="{{ format_duration($row->duration) }}"/>
            <input type="hidden" name="url" value="{{ "https://www.youtube.com/watch?v=" . $row->path }}"/>
            <input type="hidden" name="thumbnail" value="http://img.youtube.com/vi/{{ $row->path }}/0.jpg"/>
            <input type="hidden" name="size" value=""/>
            <input type="hidden" name="path" value="{{ $row->title }}"/>
            <input type="hidden" name="provider_id" value="{{ $row->path }}"/>
        @else
            <input type="hidden" name="url" value="{{ uploads_url($row->path) }}"/>
            <input type="hidden" name="thumbnail" value="{{ thumbnail($row->path) }}"/>
            <input type="hidden" name="size"
                   value="{{ (File::exists(uploads_path($row->path))) ? format_file_size(File::size(uploads_path($row->path))) : "0 MB" }}"/>
            <input type="hidden" name="path" value="{{ $row->path }}"/>
            <input type="hidden" name="duration" value=""/>
            <input type="hidden" name="provider" value=""/>
        @endif

        <input type="hidden" name="id" value="{{ $row->id }}"/>
        <input type="hidden" name="title" value="{{ $row->title }}"/>
        <input type="hidden" name="description" value="{{ $row->description }}"/>
        <input type="hidden" name="created_date" value="{{ $row->created_date }}"/>

        <i class="fa fa-check right-mark"></i>
        <div class="dz-details">
            <div class="dz-thumbnail-wrapper">

                <div class="dz-thumbnail">
                    @if($row->type == "video")
                        <i class="vid fa fa-play-circle"></i>
                    @endif
                    @if ($row->provider == "youtube")
                        <img src="http://img.youtube.com/vi/{{ $row->path }}/0.jpg">
                    @else
                        <img src="{{ thumbnail($row->path) }}">
                    @endif
                    <span class="dz-nopreview">No preview</span>
                </div>
            </div>
        </div>
    </div>
@endforeach
