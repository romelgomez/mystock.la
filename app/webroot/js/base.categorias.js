(function( categories, $) {

    /*
     Private Method
     Descripción:  Inicializa el árbol
    */
    var tree = function(){
        var treeData = $.parseJSON($('#json_tree').html());

        if(treeData != null){
            var displayTree = $('#display_tree');

            var options = {
                dragAndDrop: true,
                selectable: true,
                autoEscape: false,
                autoOpen: true,
                data: treeData
            };

            displayTree.tree(options);

            $('#tree').fadeIn();
        }else{
            $('#no-tree').fadeIn();
        }
    };

    //Public Method
    categories.init = function(){
        tree();
    }

}( window.categories = window.categories || {}, jQuery ));

categories.init();