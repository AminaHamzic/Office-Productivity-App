let expenses = [];
var idCounter = 1;

$("#expenses-form").validate({
    rules: {
        dateInput: {
            required: true,
            
        },
        description: {
            required: true,
            minlength: 3,
            maxlength: 255
            
        },
        expenseAmount: {
            required: true

        },
        category : {
            required: true
            
        }
 },
messages: {
    dateInput: {
        required: "Date is required"
    },
    description: {
        required: "Description is required",
        minlength: "Description must be at least 3 characters long",
        maxlength: "Description must be less than 255 characters long"
    },
    expenseAmount: {
        required: "Amount is required"
    },  
    category: {
        required: "Category is required"
    }
},

submitHandler: function(form, event) {
    event.preventDefault();
    blockUi("#expenses-form");
    let formData = serializeForm(form);

    let expenseAmount = parseFloat(formData.expenseAmount);
    
    let currentTotalExpenses = parseFloat($("#total-weekly-expenses").text().replace("$", ""));
    
    let newTotalExpenses = currentTotalExpenses - expenseAmount;

    $("#total-weekly-expenses").text("$" + newTotalExpenses.toFixed(2));
    localStorage.setItem("weeklyExpenses", newTotalExpenses.toFixed(2));

    formData["id"] = idCounter++;
    expenses.push(formData);

    if ($.fn.dataTable.isDataTable("#expenses-table")) {
        $("#expenses-table").DataTable().destroy();
    }
    initializeDatatable("expenses-table", expenses);

    $("#expenses-form")[0].reset(); 
    unblockUi("#expenses-form");
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
            {title: "Date", data: "dateInput"},
            {title: "Description", data: "description"},
            {title: "Expense Amount", data: "expenseAmount"},
            {title: "Category", data: "category"},
            {title: "Actions", data: "id", render: function(data) {
                return `<button class="btn btn-danger" onclick="deleteExpense(${data})">Delete</button>`;
            }},
            {title: "Actions", data: "id", render: function(data) {
                return `<button class="btn btn-info" onclick="updateExpense(${data})">Update</button>`;
            }}
        ],
        data: data
    });
};


updateExpense = (id) => {
    var selectedExpense = expenses.find(expense => expense.id === id);

    if (!selectedExpense) {
        console.log("Expense with ID " + id + " not found.");
        return; 
    }

    $("#update-expense-form input[name='expense_id']").val(selectedExpense.id);
    $("#update-expense-form input[name='update_expense_date']").val(selectedExpense.dateInput);
    $("#update-expense-form input[name='update_expense_description']").val(selectedExpense.description);
    $("#update-expense-form input[name='update_expense_amount']").val(selectedExpense.expenseAmount);
    $("#update-expense-form select[name='update_expense_category']").val(selectedExpense.category);

    $('#updateExpenseModal').modal('show');
}

function submitExpenseUpdate() {
    var id = $("#confirmSubmitButton").data("expenseId");

    console.log("Changes submitted.");

    $("#updateExpenseModal").modal("hide");
}

$("#confirmSubmitButton").click(function() {
    var id = $(this).data("expenseId");
    $("#confirmSubmitButton").data("expenseId", id); 

    submitExpenseUpdate();
});

function deleteExpense(id) {
    $("#confirmDeleteButton").data("expenseId", id);

    $("#deleteExpenseModal").modal("show");
}

$("#confirmDeleteButton").click(function() {
    var id = $(this).data("expenseId");

    console.log("Deleting expense with ID:", id);

    
    $("#deleteExpenseModal").modal("hide");
});




function loadWeeklyExpenses() {
    var savedWeeklyExpenses = localStorage.getItem("weeklyExpenses");
    if (savedWeeklyExpenses !== null) {
        $("#total-weekly-expenses").text("$" + savedWeeklyExpenses);
    }
}



loadWeeklyExpenses();


$("#save-weekly-expenses-btn").on("click", function() {
    var weeklyExpenses = $("#weekly-expenses-input").val();
    saveWeeklyExpenses(weeklyExpenses);
});
        
function saveWeeklyExpenses(weeklyExpenses) {
    console.log("Weekly expenses saved successfully:", weeklyExpenses);
    $("#total-weekly-expenses").text("$" + weeklyExpenses);
    localStorage.setItem("weeklyExpenses", weeklyExpenses);
}     
    
        



