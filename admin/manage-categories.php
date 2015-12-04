<?php 
$page = 'dashboard';
require('admin-header.php'); 
?>

<main role="main">
	<section class="panel">
        <h2>All Categories:</h2>
        <div id="display-area" class="box"></div>
    </section>

    <section class="panel">
        <h2>Add Category:</h2>
        <form action="#" method="post" class="add-category box">
            <label>Name:</label>
            <input type="text" name="name" id="name">
            <input type="submit" value="add">   
            <div class="spinner"><svg width='28px' height='28px' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="uil-spin"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><g transform="translate(50 50)"><g transform="rotate(0) translate(34 0)"><circle cx="0" cy="0" r="8" fill="#000"><animate attributeName="opacity" from="1" to="0.1" begin="0s" dur="1s" repeatCount="indefinite"></animate><animateTransform attributeName="transform" type="scale" from="1.5" to="1" begin="0s" dur="1s" repeatCount="indefinite"></animateTransform></circle></g><g transform="rotate(45) translate(34 0)"><circle cx="0" cy="0" r="8" fill="#000"><animate attributeName="opacity" from="1" to="0.1" begin="0.12s" dur="1s" repeatCount="indefinite"></animate><animateTransform attributeName="transform" type="scale" from="1.5" to="1" begin="0.12s" dur="1s" repeatCount="indefinite"></animateTransform></circle></g><g transform="rotate(90) translate(34 0)"><circle cx="0" cy="0" r="8" fill="#000"><animate attributeName="opacity" from="1" to="0.1" begin="0.25s" dur="1s" repeatCount="indefinite"></animate><animateTransform attributeName="transform" type="scale" from="1.5" to="1" begin="0.25s" dur="1s" repeatCount="indefinite"></animateTransform></circle></g><g transform="rotate(135) translate(34 0)"><circle cx="0" cy="0" r="8" fill="#000"><animate attributeName="opacity" from="1" to="0.1" begin="0.37s" dur="1s" repeatCount="indefinite"></animate><animateTransform attributeName="transform" type="scale" from="1.5" to="1" begin="0.37s" dur="1s" repeatCount="indefinite"></animateTransform></circle></g><g transform="rotate(180) translate(34 0)"><circle cx="0" cy="0" r="8" fill="#000"><animate attributeName="opacity" from="1" to="0.1" begin="0.5s" dur="1s" repeatCount="indefinite"></animate><animateTransform attributeName="transform" type="scale" from="1.5" to="1" begin="0.5s" dur="1s" repeatCount="indefinite"></animateTransform></circle></g><g transform="rotate(225) translate(34 0)"><circle cx="0" cy="0" r="8" fill="#000"><animate attributeName="opacity" from="1" to="0.1" begin="0.62s" dur="1s" repeatCount="indefinite"></animate><animateTransform attributeName="transform" type="scale" from="1.5" to="1" begin="0.62s" dur="1s" repeatCount="indefinite"></animateTransform></circle></g><g transform="rotate(270) translate(34 0)"><circle cx="0" cy="0" r="8" fill="#000"><animate attributeName="opacity" from="1" to="0.1" begin="0.75s" dur="1s" repeatCount="indefinite"></animate><animateTransform attributeName="transform" type="scale" from="1.5" to="1" begin="0.75s" dur="1s" repeatCount="indefinite"></animateTransform></circle></g><g transform="rotate(315) translate(34 0)"><circle cx="0" cy="0" r="8" fill="#000"><animate attributeName="opacity" from="1" to="0.1" begin="0.87s" dur="1s" repeatCount="indefinite"></animate><animateTransform attributeName="transform" type="scale" from="1.5" to="1" begin="0.87s" dur="1s" repeatCount="indefinite"></animateTransform></circle></g></g></svg></div>     
        </form>
    </section>

</main>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

<script type="text/javascript">
	function updateDisplay(){            
		$.ajax({                                      
			url     : 'category-process.php',   
			data    : {"action" : "display" },   
			method  : "POST",             
			success : function(response) {
				$('#display-area').html(response);
			}
		});
	};

        //DISPLAY the list immediately
        updateDisplay();

        //INSERT the category
        $("form.add-category").submit(function( event ) { 
         //stop the normal form behavior
         event.preventDefault();
         var name = $("input#name").val();
         $.ajax({                                      
         	url     : "category-process.php",   
            method  : "post",
         	data    : { 
         		"name"       : name , 
         		"action"    : "insert"
         	}, 
         	success : function(response) {
         		updateDisplay();
                //remove the text from the input
                $("input#name").val('');
                }
            });       

     });

        //DELETE a category
        $(document).on("click", "a.del", function(event){
        	event.preventDefault();

        	var list_item = $(event.target).closest("li");
        	var link = $(event.target).closest("a");


            var category_id = $(link).data("id");

            	$.ajax({                                      
            		url     : 'category-process.php',   
            		data    : { 
            			"category_id": category_id , 
            			"action"     : "delete"
            		},
                    //cute animation effect
                    success : function(response) {
                    	list_item.slideUp(300,function() {
                    		list_item.remove();
                    	});
                    }
                });        

        });

        //do stuff during and after ajax is loading (like visual feedback)
        $(document).on({
        	ajaxStart: function() { $(".spinner").show(); },
        	ajaxStop: function() { $(".spinner").hide(); }    
        });
    </script>
    <?php include('admin-footer.php'); ?>