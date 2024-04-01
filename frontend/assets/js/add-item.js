let items = [];
var idCounter = 1;

$("#items-form").validate({
    rules: {
        item: {
            required: true,
            minlength: 3,
            maxlength: 255
        },
        itemCategory: {
            required: true,
        }
    
        
 },

messages: {
    
    item: {
        required: "Item is required",
        minlength: "Item must be at least 3 characters long",
        maxlength: "Item must be less than 255 characters long"
    },
    itemCategory: {
        required: "Category is required",

    }
},

submitHandler: function(form, event) {
    event.preventDefault();
    blockUi("#items-form");
    let formData = serializeForm(form);

    //console.log("Json data is: " + JSON.stringify(formData));
    formData["id"] = idCounter++;
    items.push(formData);

    if($.fn.dataTable.isDataTable("#items-table")) {
        $("#items-table").DataTable().destroy();
    }
    initializeDatatable("items-table", items);



    $("#items-form")[0].reset(); //clean form
    console.log("items array is: " + JSON.stringify(items));


    unblockUi("#items-form");
}
});

blockUi = (element) => {
    $(element).block({
      message: '<div class="spinner-border text-primary" role="status"></div>',
      css: {
        backgroundColor: "transparent",
        border: "0",
      },
      overlayCSS: {
        backgroundColor: "#000",
        opacity: 0.25,
      },
    });
  };
  
unblockUi = (element) => {
    $(element).unblock({});
};


serializeForm = (form) => {
    let jsonResult = {};
    $.each($(form).serializeArray(), function () {
        jsonResult[this.name] = this.value;
    });
    return jsonResult;

}


initializeDatatable = (tableId, data) => {
    
    $('#' + tableId).DataTable({
        responsive: true,
        columns: [
            {title: "ID", data: "id"},
            {title: "Item", data: "item"},
            {title: "Category", data: "itemCategory"},
            {title: "Actions", data: "id", render: function(data) {
                return `<button class="btn btn-danger" onclick="deleteItem(${data})">Delete</button>`;
            }},
            {title: "Actions", data: "id", render: function(data) {
                return `<button class="btn btn-info" onclick="updateItem(${data})">Update</button>`;
            }},
            {title: "Actions", data: "id", render: function(data) {
                return `<button class="btn btn-success" onclick="itemBought(${data})">Done</button>`;
            }}
        ],
        data: data
    });
};


updateItem = (id) => {
    var selectedItem = items.find(item => item.id === id);

    if (!selectedItem) {
        console.log("Item with ID " + id + " not found.");
        return; 
    }

    $("#update-item-form input[name='item_id']").val(selectedItem.id);
    $("#update-item-form input[name='update_item']").val(selectedItem.item);
    $("#update-item-form input[name='update_itemCategory']").val(selectedItem.itemCategory);


    $('#updateItemModal').modal('show');
}

function submitItemUpdate() {
    var id = $("#confirmSubmitButton").data("itemId");

    console.log("Changes submitted for item with ID:", id);

    $("#updateItemModal").modal("hide");
}

$("#confirmSubmitButton").click(function() {
    var id = $(this).data("itemId");
    $("#confirmSubmitButton").data("itemId", id);
    submitItemUpdate(); 
});





function deleteItem(id) {
    $("#confirmDeleteButton").data("itemId", id);
    
    $("#deleteItemModal").modal("show");
}

$("#confirmDeleteButton").click(function() {
    var id = $(this).data("itemId");

    console.log("Deleting item with ID:", id);

    
    $("#deleteItemModal").modal("hide");
});


function itemBought(id){
    $("#confirmBoughtButton").data("itemId", id);

    $("#itemBoughtModal").modal("show");
}

$("#confirmBoughtButton").click(function(){
    var id = $(this).data("itemId");

    console.log("Item with ID " + id + " has been bought");

    $("#itemBoughtModal").modal("hide");
});


