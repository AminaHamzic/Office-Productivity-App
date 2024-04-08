/*document.addEventListener('DOMContentLoaded', function() {
    const inputLocation = document.getElementById('inputLocation');

    // Debounce function to limit API calls
    function debounce(func, timeout = 300){
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }

    async function fetchLocationData() {
        try {
            const response = await fetch('https://parseapi.back4app.com/classes/BosniaAndHerzegovina_City/cityid?keys=name,country', {
                headers: {
                    'X-Parse-Application-Id': '1WvrJczMga3Bq3ZJhIY4quI6ci7K2RbCuTehkZvh', // This is your app's application id
                    'X-Parse-REST-API-Key': 'mfY7zuZnxPSmGLcmqQQEW8D0i2JQf6LZoOE2i1pc', // This is your app's REST API key
                }
            });
            const data = await response.json();
            console.log(JSON.stringify(data, null, 2));
            // Here you would filter this data based on the input value
            // and update the UI accordingly (e.g., showing dropdown suggestions)
        } catch (error) {
            console.error('There was an error fetching the location data:', error);
        }
    }

    // Attach the debounce function to the input event
    inputLocation.addEventListener('input', debounce(() => fetchLocationData()));
});*/


let fields = [];
$("#profile-form").validate({
    rules: {
        inputFirstName: {
            minlength: 3,
            maxlength: 255,
            


        },
        inputOrgName: {
            minlength: 3,
            maxlength: 255
        },
        inputLastName: {
            minlength: 3,
            maxlength: 255
        },
        inputLocation: {
            minlength: 3,
            maxlength: 255
        },
        inputEmailAddress: {
            email: true
        },

        inputPhone: {
            minlength: 3,
            maxlength: 255,
        },
        inputBirthday: {
            date: true
        },

        
},
messages: {
    inputFirstName: {
        minlength: "Your first name must consist of at least 3 characters",
        maxlength: "Your first name must not exceed 255 characters"
    },
    inputLastName: {
        minlength: "Your last name must consist of at least 3 characters",
        maxlength: "Your last name must not exceed 255 characters"
    },
    inputOrgName: {
        minlength: "Your organization name must consist of at least 3 characters",
        maxlength: "Your organization name must not exceed 255 characters"
    },

    inputLocation: {
        minlength: "Your location must consist of at least 3 characters",
        maxlength: "Your location must not exceed 255 characters"
    },
    inputEmailAddress: {
        email: "The email should be in @ domain.com format"
    },

    inputPhone: {
        minlength: "Your phone number must consist of at least 3 characters",
        maxlength: "Your phone number must not exceed 255 characters",
        digit: "Your phone number must be a digit"
    },
    inputBirthday: {
        date: "Your birthdate must be a date"
    }
},

submitHandler: function(form, event) {
    event.preventDefault(); // js function that is used to prevent the default behaviour of the button
    blockUi("#profile-form");
    let formData = serializeForm(form);
    //console.log("Json data is: " + JSON.stringify(formData));
    fields.push(formData);
    $("#profile-form")[0].reset(); //clean form
    console.log("Users array is: " + JSON.stringify(fields));
    unblockUi("#profile-form");
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