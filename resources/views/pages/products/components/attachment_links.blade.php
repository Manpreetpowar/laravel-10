@foreach ($attachments as $attachment)
    <a class="fancybox preview-image-thumb"
        href="{{ url('storage/files/attachments/'.$attachment->attachment_directory.'/'.$attachment->attachment_filename)  }}"
        title="{{ \Illuminate\Support\Str::limit($attachment->attachment_filename, 60) }}"
        alt="{{ \Illuminate\Support\Str::limit($attachment->attachment_filename, 60) }}" target="_blank">View Image</a><br>
@endforeach
