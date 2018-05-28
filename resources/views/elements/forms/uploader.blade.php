<button id="{{ $id }}" class="upload-button"
	onclick="window.SITE.InitUploadFiles(window.uploadCallBacks_{{ $id }}, '{{ route('fileupload') }}', '{{ $upload_type }}', {{ ($multiple ?? true) ? 'true' : 'false' }}); return false;">
     <div class="upload-progress">
        <div class="upload-progress-status">
            <div class="upload-progress-status-text">0%</div>
        </div>
        <div class="upload-progress-text">0%</div>
     </div>   
</button>

<script type="text/javascript">
    window.uploadCallBacks_{{ $id }} = {
        start: function () {
            $('#{{ $id }} .upload-progress-status').css('width', '0%');
            $('#{{ $id }} .upload-progress-status-text').html('0%');
            $('#{{ $id }} .upload-progress-text').html('0%');
            $('#{{ $id }}').prop('disabled', true);

            if({{ ($multiple ?? true) ? 'true' : 'false' }}) {
            } else {
                
            }
        },
        success: function (data) {
            $('#{{ $id }}').prop('disabled', false);

            if({{ ($multiple ?? true) ? 'true' : 'false' }}) {
                $('{{ $result_container }}').append(SITE.template('{{ $result_template }}', {
                    detail: data.value,
                    preview: data.preview
                }));
            } else {
                $('{{ $result_container }}').html('').append(SITE.template('{{ $result_template }}', {
                    detail: data.value,
                    preview: data.preview
                }));
            }
        },
        failure: function (errors) {
            console.log(errors);
            $('#{{ $id }}').prop('disabled', false);

            if({{ ($multiple ?? true) ? 'true' : 'false' }}) {
                
            } else {
                
            }
        },
        progress: function (percent) {
            if(percent < 100) {
                $('#{{ $id }} .upload-progress-status').css('width', percent + '%');
                $('#{{ $id }} .upload-progress-status-text').html(percent.toFixed(0) + '%');
                $('#{{ $id }} .upload-progress-text').html(percent.toFixed(0) + '%');
            } else {
                $('#{{ $id }} .upload-progress-status').css('width', '100%');
                $('#{{ $id }} .upload-progress-status-text').html('Обработка...');
                $('#{{ $id }} .upload-progress-text').html('Обработка...');
            }
        }
    }
</script>