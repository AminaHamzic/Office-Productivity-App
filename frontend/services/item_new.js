var ItemService = {
    reload_item_datatable: function() {
        if ($.fn.DataTable.isDataTable('#items-table')) {
            $('#items-table').DataTable().clear().destroy();
        }
    
        $('#items-table').DataTable({
            ajax: {
                url: Constants.API_BASE_URL + "items",
                dataSrc: '',
                error: function(xhr, status, error) {
                    toastr.error("Failed to load items: " + error);
                }
            },
            columns: [
                { data: "id", title: "ID" },
                { data: "item", title: "Item" },
                { data: "category", title: "Category" }, 
                {
                    data: null,
                    title: "Actions",
                    orderable: false,
                    searchable: false,
                    defaultContent: '<button class="btn btn-primary edit-btn">Edit</button> <button class="btn btn-danger delete-btn">Delete</button>'
                }
            ],
            drawCallback: function(settings) {
                $('#items-table tbody').on('click', '.edit-btn', function() {
                    var data = $('#items-table').DataTable().row($(this).parents('tr')).data();
                    ItemService.open_edit_item_modal(data.id);
                });
                $('#items-table tbody').on('click', '.delete-btn', function() {
                    var data = $('#items-table').DataTable().row($(this).parents('tr')).data();
                    ItemService.delete_item(data.id);
                });
            }
        });
      },

  add_item: function () {
    var form = $("#items-form");
    if (form.valid()) {
        var data = Utils.serialize_form(form);
        RestClient.post(
            "items/add",
            data,
            function (response) {
                ItemService.reload_item_datatable();
                Utils.clear_form(form);
                toastr.success("Item has been added successfully.");
            },
            function (error) {
                toastr.error("Error adding the item: " + error.responseText);
            }
        );
    }
},
  
open_edit_item_modal: function(id) {
    RestClient.get("items/" + id, function(data) {
        $('#update_item').val(data.item);
        
        RestClient.get("categories", function(categories) {
            var categorySelect = $('#category_update');
            categories.forEach(function(category) {
                var isSelected = (data.category_id === category.category_id) ? ' selected' : '';
                categorySelect.append('<option value="' + category.category_id + '"' + isSelected + '>' + category.category_name + '</option>');
            });
        }, function(xhr, status, error) {
            toastr.error("Error loading categories: " + error);
        });

        $('#updateItemModal').modal('show');
    }, function(error) {
        toastr.error("Error loading item data: " + error.responseText);
    });
},

  
save_item_changes: function() {
    var form = $("#update-item-form");
    if (form.valid()) {
        var data = Utils.serialize_form(form);
        var id = $('#update_id').val(); // Make sure 'update_id' input holds the correct item ID
        console.log("Submitting update for ID:", id);
        console.log("Data submitted:", data);

        RestClient.put(
            "items/update/" + id,
            JSON.stringify(data),
            {
                contentType: 'application/json',
                success: function(response) {
                    $('#updateItemModal').modal('hide');
                    ItemService.reload_item_datatable();
                    toastr.success("Item has been updated successfully.");
                },
                error: function(error) {
                    toastr.error("Error updating the item: " + error.responseText);
                }
            }
        );
    }
},


  
  delete_item: function (id) {
      $('#deleteItemModal').data('id', id);
      $('#deleteItemModal').modal('show');
  },

  confirm_delete_item: function () {
      var id = $('#deleteItemModal').data('id');
      RestClient.delete(
          "items/delete/" + id,
          {},
          function (response) {
              $('#deleteItemModal').modal('hide');
              ItemService.reload_item_datatable();
              toastr.success("Item has been deleted successfully.");
          },
          function (error) {
              $('#deleteItemModal').modal('hide');
              toastr.error("Error deleting the item: " + error.responseText);
          }
      );
  },

  load_categories: function() {
    RestClient.get("categories", function(categories) {
        console.log("Loaded categories:", categories);
        var categorySelect = $('#category_id');
        categorySelect.empty();
        categorySelect.append('<option selected>Select Category</option>');
        
        categories.forEach(function(category) {
            categorySelect.append(new Option(category.category_name, category.category_id));
        });
    }, function(xhr, status, error) {
        toastr.error("Error loading categories: " + error);
        console.error("Error details:", xhr, status, error);
    });
},


};
  
