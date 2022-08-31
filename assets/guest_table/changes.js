
$( document ).ready(function() {



          var tbl = document.getElementById('invoiceTable');

            $(document).on('change', '#code', function() {                      
              
              var row_index = $(this).parent().parent().index();
              var col_index = $(this).index();


              // var e = document.getElementById("code");
              // var strUser = e.options[e.selectedIndex].text;

              // alert(strUser);

             //alert(tbl.rows[row_index+1].cells[0].options[e.selectedIndex].text);


              /*
              $(this).closest('tr').find('td').each(function() {
                  var that = $(this);
                 
                     alert(that.find('select').val());

                     input = that.find('select').val();
                  
               }); 
              */


              var input = tbl.rows[row_index].cells[0].children[0].value;

              //var link - codeLink+"/"+input;


            $.get(codeLink, 'input=' + input , function(data, status){
                
                
                var obj = JSON.parse(JSON.stringify(data));
                                
                var id = obj['data']['id'];
                var code = obj['data']['code'];
                var size = obj['data']['size'];
                var desc = obj['data']['description'];
                var unitPrice = obj['data']['unitPrice'];
                //var price = obj['data']['description'];

                tbl.rows[row_index].cells[0].innerHTML = code;
                tbl.rows[row_index].cells[1].innerHTML = size;
                tbl.rows[row_index].cells[2].innerHTML = desc;
                tbl.rows[row_index].cells[3].innerHTML = unitPrice;
                tbl.rows[row_index].cells[4].innerHTML = '<input type="text" id="qty" class="form-control" value="1">';
                tbl.rows[row_index].cells[5].innerHTML = unitPrice;

            });              

            });  

            $(document).on('change', '#desc', function() {              
              
              var row_index = $(this).parent().parent().index();
              var col_index = $(this).index();

              var input = tbl.rows[row_index].cells[2].children[0].value;

            $.get( descLink, 'input=' + input , function(data, status){
                
                var obj = JSON.parse(JSON.stringify(data));

                var id = obj['data']['id'];
                var code = obj['data']['code'];
                var size = obj['data']['size'];
                var desc = obj['data']['description'];
                var unitPrice = obj['data']['unitPrice'];

                tbl.rows[row_index].cells[0].innerHTML = code;
                tbl.rows[row_index].cells[1].innerHTML = size;
                tbl.rows[row_index].cells[2].innerHTML = desc;
                tbl.rows[row_index].cells[3].innerHTML = unitPrice;
                tbl.rows[row_index].cells[4].innerHTML = '<input type="text" id="qty" class="form-control" value="1">';
                tbl.rows[row_index].cells[5].innerHTML = unitPrice;

            });              

            });  

            $(document).on('change', '#qty', function() {              
              
              var row_index = $(this).parent().parent().index();
              var col_index = $(this).index();

              var qty = tbl.rows[row_index].cells[4].children[0].value;

              var unitPrice = tbl.rows[row_index].cells[3].innerHTML;

              //alert(number(qty)*number(unitPrice));

              var qtyStr = parseInt(qty.replace("-",""));

              if(!Number.isInteger(qtyStr)){
                tbl.rows[row_index].cells[4].innerHTML = '<input type="text" id="qty" class="form-control" value="1">';
                tbl.rows[row_index].cells[5].innerHTML = 1*Number(unitPrice);
              }
              else if(qtyStr==0){
                tbl.rows[row_index].cells[4].innerHTML = '<input type="text" id="qty" class="form-control" value="1">';
                tbl.rows[row_index].cells[5].innerHTML = 1*Number(unitPrice);
              }
              else{
                tbl.rows[row_index].cells[4].innerHTML = '<input type="text" id="qty" class="form-control" value="'+qtyStr+'">';
                tbl.rows[row_index].cells[5].innerHTML = (parseInt(qty.replace("-",""))*Number(unitPrice)).toFixed(2);  
              }

                                  

            });  

            $(document).on('click', '#invoice', function() { 



              var $rows = $('#table').find('tr:not(:hidden)');
              var headers = [];
              var data = [];   

              var j = 0;
              $('#export').text("");  
              
              // Get the headers (add special header logic here)
              $($rows.shift()).find('th:not(:empty)').each(function () {
                //var text = ($(this).text().replace(/\s+/g, '_');
                headers.push(($(this).text().toLowerCase()).replace(/\s+/g, '_'));
              });

              //alert("Row Count: " + $rows.length);
              
              // Turn all existing rows into a loopable array
              $rows.each(function () {
                var $td = $(this).find('td');
                var h = {};                
                
                // Use the headers from earlier to name our hash keys
                headers.forEach(function (header, i) {
                  //alert(i);
                  h[header] = $td.eq(i).text();

                  if(i==4){
                    h[header] = $td.eq(i).children().val();
                  }
                  
                });
                
                data.push(h);

                //alert($td.eq(0).children().val());

                 if($td.eq(0).children().val()==""){                     
                     checkEmpty();
                     return false;
                  }
                  else{
                    j++;
                  }     

                // Output the result
                if(j==$rows.length){

                  //checkEmpty();

                  $('#export').text(JSON.stringify(data));


                  var jsonData = JSON.stringify(data);
                  var customer = $("#customerName").val();
                 
                  var taxCheck = $('#isTax').is(":checked");

                  var tax = 0;

                  if(taxCheck==true){
                      tax = 1;
                  }

                  if(customer==""){
                    $.alert({
                            type: 'red',
                            title: 'Alert!',
                            content: "Please select a customer",
                        });
                  }
                  else{

                  var serializedData = 'customer='+ customer + '&jsonData='+ jsonData + '&tax='+ tax + '&_token=' + token;   

                  $.confirm({
                          type: 'green',
                          title: 'Confirm Sales!',
                          content: 'Do you want to print the invoice? ',
                          buttons: {
                              confirm: {
                                  btnClass: 'btn-green',
                                  action : function () {
                                      $.ajax({
                                      url: invoicingLink,
                                      type: "post",
                                      data: serializedData,
                                      success: function (data) {

                                        if(data['data']=="Success"){                                        
                                          // for (var i = 0; i < data['IDs'].length; i++){
                                          //     var obj = data['IDs'][i];

                                          //     var win = window.open(invoiceLink+"/"+obj, '_blank');                                              
                                          // }

                                          window.location.href = invoiceReloadLink;                                          
                                        }
                                        else{
                                          $.alert({
                                              type: 'red',
                                              title: 'Alert!',
                                              content: data,
                                          });
                                        }
                                      },
                                      error: function (error) {    
                                        console.log(error);
                                      } 
                                      });

                                  }
                              },                              
                              cancel:  function () {}
                          }
                      });             
                  
                  
                  }
                  
                }           
                
              });     
              
            }); 

            function checkEmpty(){
              $.alert({
                            type: 'red',
                            title: 'Error!',
                            content: "Check if any rows are empty",
                        });
            }

});

function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}