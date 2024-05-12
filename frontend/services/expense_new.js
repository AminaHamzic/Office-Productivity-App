var ExpenseService = {

    reload_expense_datatable: function () {
      Utils.get_datatable(
        "expenses-table",
        Constants.API_BASE_URL + "get_expense.php",
        [
          { data: "id" },
          { data: "dateInput" },
          { data: "description" },
          { data: "expenseAmount" },
            { data: "category" },
          { data: "action" }
        ]
      );
    },

    add_expense: function () {
      var form = $("#expenses-form");
      if (form.valid()) {
        var data = Utils.serialize_form(form);
        RestClient.post(
          "add_expense.php",
          data,
          function (data) {
            ExpenseService.reload_expense_datatable();
            Utils.clear_form(form);
          },
          
        );
      }
    },
    
    open_edit_expense_modal: function (id) {
      RestClient.get("update_expense.php?id=" + id, function (data) {
        $('#update_expense').val(data.expense);
        $('#category_update').val(data.category);
        
        
        $('#updateExpenseModal').modal('show');
      }, function (error) {
        toastr.error("Error loading expense data.");
      });
    },
    
    save_expense_changes: function () {
      var form = $("#update-expense-form");
      if (form.valid()) {
        var data = Utils.serialize_form(form);
        RestClient.post(
          "update_expense.php", 
          data, 
          function (response) {
            $('#updateExpenseModal').modal('hide');
            ExpenseService.reload_expense_datatable();
            toastr.success("expense has been updated successfully.");
          },
          function (error) {
            $('#updateExpenseModal').modal('hide');
            toastr.error("Error updating the expense.");
          }
        );
      }
    },
    

  
    delete_expense: function (id) {
      $('#deleteExpenseModal').data('id', id);
      $('#deleteExpenseModal').modal('show');
    },

    confirm_delete_expense: function () {
      var id = $('#deleteExpenseModal').data('id');

      RestClient.delete(
        "delete_expense.php?id=" + id,
        {},
        function (data) {
          $('#deleteExpenseModal').modal('hide');
      
          ExpenseService.reload_expense_datatable();
          
          toastr.success("Expense has been deleted successfully.");
        },
        function (error) {
          $('#deleteExpenseModal').modal('hide');
          
          toastr.error("Error deleting the expense.");
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


  