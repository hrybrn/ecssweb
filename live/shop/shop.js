function getItems(search){
    $.ajax({
        url: 'getItems.php',
        type: 'get',
        data: 'search=' + search,
        dataType: 'json',
        success: function(items){
            //do stuff

            $(items).each(function(index, itemData){
                $(itemData).each(function(name, data){
                    
                });
            });
        }
    });
}

function getItem(id){
    $.ajax({
       url: 'getItem.php',
       type: 'get',
       data: 'itemID=' + id,
       dataType: 'json',
       success: function(item){
           //do stuff
       }
    });
}