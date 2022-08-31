
// Get the <datalist> and <input> elements.
var dataList = document.getElementById('json-datalist');
var input = document.getElementById('ajax');  

// Create a new XMLHttpRequest.
var request = new XMLHttpRequest();

// Handle state changes for the request.
request.onreadystatechange = function(response) {
  if (request.readyState === 4) {
    if (request.status === 200) {
      // Parse the JSON
      var jsonOptions = JSON.parse(request.responseText);
  
      // Loop over the JSON array.
      jsonOptions.forEach(function(item) {
        // Create a new <option> element.
        var option = document.createElement('option');
        // Set the value using the item in the JSON array.
        option.value = item;
        // Add the <option> element to the <datalist>.
        dataList.appendChild(option);
      });
      
      // Update the placeholder text.
      input.placeholder = "";
    } else {
      // An error occured :(
      input.placeholder = "Couldn't load datalist options :(";
    }
  }
};

request.open('GET', 'php/customerData.php', true);
request.send();
    

// Get the <datalist> and <input> elements.
var dataList1 = document.getElementById('code-datalist');
var input1 = document.getElementById('code');  

// Create a new XMLHttpRequest.
var request1 = new XMLHttpRequest();

// Handle state changes for the request.
request1.onreadystatechange = function(response) {
  if (request1.readyState === 4) {
    if (request1.status === 200) {
      // Parse the JSON
      var jsonOptions1 = JSON.parse(request1.responseText);
  
      // Loop over the JSON array.
      jsonOptions1.forEach(function(item) {
        // Create a new <option> element.
        var option1 = document.createElement('option');
        // Set the value using the item in the JSON array.
        option1.value = item;
        // Add the <option> element to the <datalist>.
        dataList1.appendChild(option1);
      });
      
      // Update the placeholder text.
      input1.placeholder = "";
    } else {
      // An error occured :(
      input1.placeholder = "Couldn't load datalist options :(";
    }
  }
};

request1.open('GET', 'php/invoiceData.php?data=code', true);
request1.send();


// Get the <datalist> and <input> elements.
var dataList2 = document.getElementById('desc-datalist');
var input2 = document.getElementById('desc');  

// Create a new XMLHttpRequest.
var request2 = new XMLHttpRequest();

// Handle state changes for the request.
request2.onreadystatechange = function(response) {
  if (request2.readyState === 4) {
    if (request2.status === 200) {
      // Parse the JSON
      var jsonOptions2 = JSON.parse(request2.responseText);
  
      // Loop over the JSON array.
      jsonOptions2.forEach(function(item) {
        // Create a new <option> element.
        var option2 = document.createElement('option');
        // Set the value using the item in the JSON array.
        option2.value = item;
        // Add the <option> element to the <datalist>.
        dataList2.appendChild(option2);
      });
      
      // Update the placeholder text.
      input2.placeholder = "";
    } else {
      // An error occured :(
      input2.placeholder = "Couldn't load datalist options :(";
    }
  }
};

request2.open('GET', 'php/invoiceData.php?data=desc', true);
request2.send();
