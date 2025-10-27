// TinyMCE Configuration
window.tinymceConfig = {
    height: 500,
    menubar: false,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
    content_style: 'body { font-family: -apple-system, color: white; BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
    branding: false,
    promotion: false,
    setup: function (editor) {
        editor.on('change', function () {
            editor.save();
        });
    },
    // Image upload configuration
    images_upload_handler: function (blobInfo, success, failure) {
        // You can implement image upload here
        // For now, we'll use a placeholder
        const formData = new FormData();
        formData.append('image', blobInfo.blob(), blobInfo.filename());

        fetch('/api/upload-image', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                success(result.url);
            } else {
                failure('Image upload failed');
            }
        })
        .catch(() => {
            failure('Image upload failed');
        });
    }
};

// Initialize TinyMCE with custom config
function initTinyMCE(selector) {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: selector,
            ...window.tinymceConfig
        });
    }
}
