<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html >
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/themes/mytheme.css"/>
	<link rel="stylesheet" href="css/themes/jquery.mobile.icons.min.css"/>
        <link rel="stylesheet" href="css/jquery.mobile-1.4.5.min.css">
	<link rel="stylesheet" href="css/jquery-ui.css">
	<script src="js/jquery-2.1.4.min.js"></script>
	<script src="js/jquery-ui.js"></script>
        <script src="js/offline.js"></script>
	<script src="js/jquery.mobile-1.4.5.min.js"></script>
	<script src="phonegap.js"></script>
        <title></title>
        <style>
            div+div{
                margin-top: .3%;
                margin-bottom: 1%;
            }
        </style>
        <script>
            //send response when url is passed
            function sendRequest(u){
                    var obj=$.ajax({url:u,async:false});
                     var result=$.parseJSON(obj.responseText);
                    return result;
                                
		}
            
            function scanBarcode(){
		cordova.plugins.barcodeScanner.scan(
			function (result) {
                            $("#barcode").val(result.text)
                        },
                            function (error) {
				alert("Scanning failed: " + error);
                            }
		);
            }
            
            //save all transaction
            function saveTransaction(){
                $("#sellItem").click(function(ev){
                    ev.preventDefault();
                var phoneNo=$("#phoneNo").val();
                var grosspay=$("#gross").val();
                var transaction="gross="+grosspay+"&phoneNo="+phoneNo;
                var theUrl="http://cs.ashesi.edu.gh/~csashesi/class2016/beatrice-lungahu/MobileWeb/AppPointofSale/request.php?cmd=4&"+transaction;
                var obj3 = sendRequest(theUrl);
                if(obj3.result==1){
                    
                    $('#messageTransaction').text(obj3.message);
                    $('#messageTransaction').show();
                }
                else{
                    $('#messageTransaction').text(obj3.message);
                    $('#messageTransaction').css("backgroundColor","red");
                }
                });
                
            }
            function saveToDataBase(){
                $("#submitData").submit(function(ev){
                    ev.preventDefault();
                    var barcode = $("#barcode").val();
                    var product = $("#product").val();
                    var price = $("#price").val();
                    var picture = $("#image").val();
                    var stringVal = "barcode="+barcode+"&product="+product+"&price="+price+"&picture="+picture;
                    var theUrl ="request.php?cmd=1&"+stringVal;
                    var obj = sendRequest(theUrl);
                    
                    if(obj.result==1)
                    {
			$('#showMessage').text(obj.message);
			$('#showMessage').show();
                    }
                    else
                    {
			$('#showMessage').text("Error adding");
			$('#showMessage').css("backgroundColor","red");
                    }

		});

            }
            //loading itemd to page three
            $(document).on("pagecreate","#pagethree",function(){
		
		var theUrl = "http://cs.ashesi.edu.gh/~csashesi/class2016/beatrice-lungahu/MobileWeb/AppPointofSale/request.php?cmd=2";

			var obj=sendRequest(theUrl);
		
			if(obj.result==1){
                            var totalAmount=0;

                            var list="";
                             var prdctID=""
			$.each(obj.values,function(i,value){
                            prdctID=value.Product_id;
                            //alert(prdctID);
				list+='<li><a href="#" class="details" id='+prdctID+'>'+value.productName+'</a></li>';
			});
	
			$("#itemsBought").append(list).promise().done(function(){
				$(this).on("click",".details",function(e){
                                    e.preventDefault();
                                    var theUrl2="http://cs.ashesi.edu.gh/~csashesi/class2016/beatrice-lungahu/MobileWeb/AppPointofSale/request.php?cmd=3&Product_id="+this.id;
                                    var obj2 = sendRequest(theUrl2);
                                   
					
                                        
                                     var row="";
                                          $.each(obj2.data,function(i,value){
                                              row+='<tr><td>'+value.productName+'</td><td>'+value.price+'</td></tr>';
                                              var valuePrice=parseFloat(value.price);
                                              totalAmount+=valuePrice;
                                          })
                                          $("tbody").append(row);
				});
				$("#itemsBought").listview("refresh");
			});
                        $("#grossAmount").click(function(ev){
                            ev.preventDefault();
                            $("#gross").val(totalAmount);
                            
                        });
                        }
		});
                
                
           //loading values to page one
            
        $(document).on("pagecreate","#pageone",function(){
		//alert("ready");
		var theUrl = "request.php?cmd=2";

			var obj=sendRequest(theUrl);

			if(obj.result==1){
                            //alert(obj.result);

			var row;
			
			$.each(obj.values,function(i,value){
				row+='<tr><td>'+value.productName+'</td>'+
                                        '<td>'+value.barcode+'</td>'+'<td>'+
                                        '<td>'+value.price+'</td>'+'<td><a href="" data-theme="a" data-rel="popup"\n\
                            data-position-to="window" data-transition="pop"></a>+</td></tr>'
			});

			$("tbody").append(row);
                        }
		});
                
		
                        
                        
                /*move to the nnext page*/
		function navigateView() {
                    if (condition) {
			$.mobile.changePage("#pagetwo");
                    }
		}

		/*move to the nnext page*/
		function navigateHome() {
                    if (condition) {
			$.mobile.changePage("#pageone");
                    }
		}
                function navigatethree() {
                    if (condition) {
			$.mobile.changePage("#pagethree");
                    }
		}
		
        </script>
    </head>
    <body>
        <p id="status">Online</p>
       <!--start of page one-->
	<div data-role="page" id="pageone">
            <!--header-->
            <div data-role="header"style="overflow:hidden" data-position="inline">
		<h1> Point Of Sale</h1>
                <a href="#" data-icon="gear" class="ui-btn-right">Option</a>
                <div data-role="navbar">
                  <ul>
                      <li><a href="#pageone"class="ui-btn ui-icon-home ui-btn-icon-left" onclick="navigateHome()">Home</a></li>
                      <li><a href="#pagetwo" class=" ui-btn ui-btn-inline  ui-corner-all ui-btn-icon-left ui-icon-grid" onclick="navigateView()">Add Items</a></li>
                      <li><a href="#pagethree" class=" ui-btn ui-btn-inline  ui-corner-all ui-btn-icon-left ui-icon-shop" onclick="navigatethree()">Sell</a></li>
                  </ul>
                </div>
            </div>

            <!--content-->
            <div data-role="main" class="ui-content">
                <table data-role="table"  class="ui-responsive table-stroke"padding="5%">
                    <thead>
                        <tr>
                            
                            <th>PRODUCT NAME<th>
                            <th>BARCODE</th>
                            <th>PRICE</th>
                            
                        </tr>
                    </thead>
                    <tbody data-split-icon="plus"data-theeme="a" data-split-theme="b"data-inset="true">
                        
                    </tbody>
                </table>
            </div>
			
            <div data-role="footer" id="showMessage">
		<h1 id="message">  </h1>

            </div>
	</div>

	<!--page two, view page-->
	<div data-role="page" id="pagetwo">
            <!--header-->
            <div data-role="header"style="overflow:hidden" data-position="inline">
		<h1> Point Of Sale</h1>
                <a href="#" data-icon="gear" class="ui-btn-right">Option</a>
                <div data-role="navbar">
                  <ul>
                      <li><a href="#pageone"class="ui-btn ui-icon-home ui-btn-icon-left" onclick="navigateHome()">Home</a></li>
                      <li><a href="#pagetwo" class=" ui-btn ui-btn-inline  ui-corner-all ui-btn-icon-left ui-icon-grid" onclick="navigateView()">Add Items</a></li>
                      <li><a href="#pagethree" class=" ui-btn ui-btn-inline  ui-corner-all ui-btn-icon-left ui-icon-shop" onclick="navigatethree()">Sell</a></li>
                  </ul>
                </div>
            </div>

            <!--content-->
            <div role="main"class="ui-content">
             <!--  <div data-role="collapsible">-->
		<input type="button" value ="SCAN" id="btnScan" onclick="scanBarcode()">
		<form method="GET" action="insert.php" id="submitData">
                    <label for="Reading">Barcode:</label>
                    <input type="text" name="barcode" id="barcode">
                    <label for="captured">Product Name:</label>
                    <input type="text" name="product" id="product" >
                    <label for="date">Price: </label>
                    <input type="text"name="price" id="price">
                    <label for="location">Image:</label>
                    <input type="file" name="image" id="image"accept="image/*"capture>
                    <input type="submit" value="Submit Button"  onclick="saveToDataBase()">		
            </div>
            
            <div data-role="footer" id="showMessage">
		<h1 id="message">  </h1>
            </div>
        </div>
			
        <!--page three -->
	<div data-role="page" id="pagethree">
            <div data-role="header"style="overflow:hidden" data-position="inline">
		<h1> Point Of Sale</h1>
                <a href="#" data-icon="gear" class="ui-btn-right">Option</a>
                <div data-role="navbar">
                  <ul>
                      <li><a href="#pageone"class="ui-btn ui-icon-home ui-btn-icon-left" onclick="navigateHome()">Home</a></li>
                      <li><a href="#pagetwo" class=" ui-btn ui-btn-inline  ui-corner-all ui-btn-icon-left ui-icon-grid" onclick="navigateView()">Add Items</a></li>
                      <li><a href="#pagethree" class=" ui-btn ui-btn-inline  ui-corner-all ui-btn-icon-left ui-icon-shop" onclick="navigatethree()">Sell</a></li>
                  </ul>
                </div>
            </div>
            <div data-role="content"id="further-details"></div>
            
            <ul data-role="listview" data-split-icon="plus" data-datatheme="a" data-split-theme="a"data-inset="true">
                <li data-role="collapsible" data-iconpos="right"data-inset="false">
                    <h2>SELECT ITEMS BEING BOUGHT</h2>
                    <ul data-role="listview" data-theme="b"id="itemsBought">
                        
                    </ul>
                </li>
            </ul>
            <div>
                <label for="phoneNo"> Phone Number: </label>
                <input type="text" data-mini="true"name="phoneNo" id="phoneNo" placeholder="+233505258170" span>
            </div>
            
            <table data-role="table"id="" data-mode=""class="ui-responsive table-stroke">
                <thead>
                    <TR>
                        <th>ITEM</th>
                        <th>PRICE</th>
                    </TR>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div id="totalAmount">
                <button id="grossAmount" class="ui-btn">Total Amount</button>
            
                <input type="text"name="gross" id="gross" data-mini="true">
                <button id="sellItem" class="ui-btn" onclick="saveTransaction()">Sell</button>
                
            </div>
			<!--footer-->
            <div data-role="footer" id="showMessage">
		<h1 id="messageTransaction">  </h1>

            </div>
        </div>
    </body>
</html>
