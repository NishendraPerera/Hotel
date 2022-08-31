

var $TABLE = $('#table');
var $BTN = $('#export-btn');
var $EXPORT = $('#export');

$('.table-add').click(function () {

 var $clone = $TABLE.find('tr.hide').clone(true).removeClass('hide');

  //var $clone = '<tr><td ><select data-placeholder="Select Code" style="width:350px;" class="chosen-select-no-results" id="code"><?php  echo $codeSelect;  ?></select></td><td ></td><td ><select data-placeholder="Select Description" style="width:350px;" class="chosen-select-no-results" id="desc"><?php  echo $descSelect;  ?></select></td><td ></td> <td ></td><td ></td> <td><span class="table-remove glyphicon glyphicon-remove"></span> </td> <td> <span class="table-up glyphicon glyphicon-arrow-up"></span><span class="table-down glyphicon glyphicon-arrow-down"></span></td></tr>';
  $TABLE.find('table tr:last').after($clone);

});

$('.table-remove').click(function () {
  $(this).parents('tr').detach();
  
});

$('.table-up').click(function () {
  var $row = $(this).parents('tr');
  if ($row.index() === 1) return; // Don't go above the header
  $row.prev().before($row.get(0));
});

$('.table-down').click(function () {
  var $row = $(this).parents('tr');
  $row.next().after($row.get(0));
});

// A few jQuery helpers for exporting only
jQuery.fn.pop = [].pop;
jQuery.fn.shift = [].shift;

$BTN.click(function () {

  var $rows = $TABLE.find('tr:not(:hidden)');
  var headers = [];
  var data = [];
  
  
  // Get the headers (add special header logic here)
  $($rows.shift()).find('th:not(:empty)').each(function () {
    headers.push($(this).text().toLowerCase());
  });
  
  // Turn all existing rows into a loopable array
  $rows.each(function () {
    var $td = $(this).find('td');
    var h = {};
    
    // Use the headers from earlier to name our hash keys
    headers.forEach(function (header, i) {
      //alert("i");
      h[header] = $td.eq(i).children().val();

      /*
      if(i==4){
        h[header] = $td.eq(i).children().val();
      }
      */
    });
    
    data.push(h);
  });
  
  // Output the result
  $EXPORT.text(JSON.stringify(data));
});

