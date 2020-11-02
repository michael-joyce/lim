function getTinyMceConfig(editorUploadPath) {

    return {
        branding: false,
        selector: '.tinymce',
        plugins: 'hr image imagetools link lists paste wordcount code advlist charmap preview searchreplace',
        relative_urls: false,
        convert_urls: false,
        height: 320,
        menubar: 'edit insert view format tools',

        toolbar: 'undo redo | styleselect | paste | bold italic | alignleft '
            + 'aligncenter alignright alignjustify | '
            + 'bullist numlist outdent indent | link | code',

        image_caption: true,
        images_upload_url: editorUploadPath,
        images_upload_credentials: true,
        image_advtab: true,
        image_title: true,

        style_formats_merge: true,
        cleanup: true,
        remove_linebreaks: true,
        convert_newlines_to_brs: false,
        inline_styles : false,
        entity_encoding: 'raw',
        paste_auto_cleanup_on_paste: true
    };

}
