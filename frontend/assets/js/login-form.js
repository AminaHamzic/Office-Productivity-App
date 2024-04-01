let users = [];
$("#login-form").validate({
    rules: {
        
        loginEmail: {
            required: true,
            email: true,
            maxlength: 255
        },
        loginPassword: {
            required: true,
            minlength: 6,
            maxlength: 255
        }
},
messages: {
    loginEmail: {
        required: "Please enter your email",
        email: "Please enter a valid email",
        maxlength: "Email cannot be more than 255 characters"
    },
    loginPassword: {
        required: "Please enter your password",
        minlength: "Password must be at least 6 characters",
        maxlength: "Password cannot be more than 255 characters"
    }
},

submitHandler: function(form, event) {
    event.preventDefault(); // js function that is used to prevent the default behaviour of the button
    blockUi("#login-form");
    let formData = serializeForm(form);
    //console.log("Json data is: " + JSON.stringify(formData));
    users.push(formData);
    $("#login-form")[0].reset(); //clean form
    console.log("Users array is: " + JSON.stringify(users));
    window.location.href = "admin-dashboard.html";
    unblockUi("#login-form");
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