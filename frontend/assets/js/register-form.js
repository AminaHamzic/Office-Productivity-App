let users = [];
$("#register-form").validate({
    rules: {
        username: {
            required: true,
            minlength: 3,
            maxlength: 255
        },
        email: {
            required: true,
            email: true,
            maxlength: 255
        },
        password: {
            required: true,
            minlength: 6,
            maxlength: 255
        },
        confirm_password: {
            equalTo: "#password",
    }
},
messages: {
    username: {
        required: "Please enter your username",
        minlength: "Your username must consist of at least 3 characters",
        maxlength: "Your username must not exceed 255 characters"
    },
    email: {
        required: "Please enter your email",
        email: "Please enter a valid email",
        maxlength: "Your email must not exceed 255 characters"
    },
    password: {
        required: "Please enter your password",
        minlength: "Your password must consist of at least 6 characters",
        maxlength: "Your password must not exceed 255 characters"
    },
    confirm_password: {
        equalTo: "Password and Confirm Password must match"
    }
},

submitHandler: function(form, event) {
    event.preventDefault(); // js function that is used to prevent the default behaviour of the button
    blockUi("#register-form");
    let formData = serializeForm(form);
    //console.log("Json data is: " + JSON.stringify(formData));
    users.push(formData);
    $("#register-form")[0].reset(); //clean form
    console.log("Users array is: " + JSON.stringify(users));
    unblockUi("#register-form");
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