(function() {
    tinymce.PluginManager.add('button', function(editor, url) {
        editor.addButton('button', {
            text: 'Insert URL',
            icon: false,
            onclick: function() {
                let url = prompt("Enter the URL:");
                if (url != null && url.trim() !== '') {
                    editor.insertContent('<a href="' + url + '">' + url + '</a>');
                }
            }
        });
    });
})();
