var ItemService = {

    reload_item_datatable: function () {
      Utils.get_datatable(
        "items-table",
        Constants.API_BASE_URL + "get_item.php",
        [
          { data: "id" },
          { data: "item" },
          { data: "category" },
          { data: "action" },
        ]
      );
    },

    add_item: function () {
      var form = $("#items-form");
      if (form.valid()) {
        var data = Utils.serialize_form(form);
        RestClient.post(
          "add_item.php",
          data,
          function (data) {
            ItemService.reload_item_datatable();
            Utils.clear_form(form);
          },
          
        );
      }
    },
    
    open_edit_item_modal: function (id) {
      // Make a REST call to fetch the item data
      RestClient.get("update_item.php?id=" + id, function (data) {
        $('#update_item').val(data.item);
        $('#category_update').val(data.category);
        
        
        $('#updateItemModal').modal('show');
      }, function (error) {
        toastr.error("Error loading item data.");
      });
    },
    
    save_item_changes: function () {
      var form = $("#update-item-form");
      if (form.valid()) {
        var data = Utils.serialize_form(form);
        RestClient.post(
          "update_item.php", 
          data, 
          function (response) {
            $('#updateItemModal').modal('hide');
            ItemService.reload_item_datatable();
            toastr.success("item has been updated successfully.");
          },
          function (error) {
            $('#updateItemModal').modal('hide');
            toastr.error("Error updating the item.");
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
        "delete_item.php?id=" + id,
        {},
        function (data) {
          $('#deleteItemModal').modal('hide');
      
          ItemService.reload_item_datatable();
          
          toastr.success("item has been deleted successfully.");
        },
        function (error) {
          $('#deleteItemModal').modal('hide');
          
          toastr.error("Error deleting the item.");
        }
      );
    },

    load_categories: function() {
      RestClient.get("get_categories.php", function(categories) {
        var categorySelect = $('#category');
        categorySelect.empty(); 
        categorySelect.append('<option selected>Select category</option>');
    
        categories.forEach(function(category) {
            categorySelect.append(new Option(category.category_name, category.id));
        });
    
        console.log("Loaded categories:", categories);
      }, function(xhr, status, error) {
        toastr.error("Error loading categories: " + error);
        console.error("Error details:", xhr, status, error); 
      });
    },
    

  }


  