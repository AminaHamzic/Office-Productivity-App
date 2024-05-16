var ExpenseService = {

  reload_expense_datatable: function () {
      Utils.get_datatable(
          "expenses-table",
          Constants.API_BASE_URL + "expenses", 
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
              Constants.API_BASE_URL + "expenses/add",
              data,
              function (response) {
                  ExpenseService.reload_expense_datatable();
                  Utils.clear_form(form);
                  toastr.success("Expense added successfully.");
              },
              function (error) {
                  toastr.error("Error adding the expense: " + error.responseText);
              }
          );
      }
  },
  
  open_edit_expense_modal: function (expense_id) {
      RestClient.get(Constants.API_BASE_URL + "expenses/" + expense_id, function (expense) {
          $('#update_expense').val(expense.description); 
          $('#category_update').val(expense.category); 
          $('#updateExpenseModal').modal('show');
      }, function (error) {
          toastr.error("Error loading expense data: " + error.responseText);
      });
  },
  
  save_expense_changes: function () {
      var form = $("#update-expense-form");
      if (form.valid()) {
          var data = Utils.serialize_form(form);
          var expense_id = $('#update_id').val();
          RestClient.put(
              Constants.API_BASE_URL + "expenses/update/" + expense_id,
              JSON.stringify(data),
              {
                  contentType: 'application/json',
                  success: function (response) {
                      $('#updateExpenseModal').modal('hide');
                      ExpenseService.reload_expense_datatable();
                      toastr.success("Expense updated successfully.");
                  },
                  error: function (error) {
                      toastr.error("Error updating the expense: " + error.responseText);
                  }
              }
          );
      }
  },

  delete_expense: function (expense_id) {
      $('#deleteExpenseModal').data('id', expense_id);
      $('#deleteExpenseModal').modal('show');
  },

  confirm_delete_expense: function () {
      var expense_id = $('#deleteExpenseModal').data('id');
      RestClient.delete(
          Constants.API_BASE_URL + "expenses/delete/" + expense_id,
          {},
          function (response) {
              $('#deleteExpenseModal').modal('hide');
              ExpenseService.reload_expense_datatable();
              toastr.success("Expense deleted successfully.");
          },
          function (error) {
              $('#deleteExpenseModal').modal('hide');
              toastr.error("Error deleting the expense: " + error.responseText);
          }
      );
  },

  load_categories: function() {
      RestClient.get(Constants.API_BASE_URL + "categories", function(categories) {
          var categorySelect = $('#category_id');
          categorySelect.empty();
          categorySelect.append('<option selected>Select Category</option>');
          
          categories.forEach(function(category) {
              categorySelect.append(new Option(category.category_name, category.category_id));
          });
      }, function(xhr, status, error) {
          toastr.error("Error loading categories: " + error);
      });
  },

};
