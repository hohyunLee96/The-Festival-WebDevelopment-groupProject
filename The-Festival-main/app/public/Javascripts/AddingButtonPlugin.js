tinymce.PluginManager.add('myplugin', function(editor, url) {
    editor.addButton('mybutton', {
        text: 'My Button',
        icon: false,
        onclick: function() {
            editor.insertContent('Hello World!');
        }
    });
});

