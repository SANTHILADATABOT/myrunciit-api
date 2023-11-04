$("body").on("click", ".colrs", function () {
  var here = $(this);
  var rowid = here.closest("tr").data("rowid");
  var val = here.closest("li").find("input").val();
  if (val == "undefined") {
    val = "";
  }
  val = val.split(",").join("-");
  val = val.replace(")", "--");
  val = val.replace("(", "---");

  $.ajax({
    url: base_url + "index.php/home/cart/upd_color/" + rowid + "/" + val,
    beforeSend: function () { },
    success: function () {
      //other option
      reload_header_cart();
    },
    error: function (e) {
      console.log(e);
    },
  });
});

let couponsAllowedToShow = [];

function others_count() {
  update_calc_cart();
}

function check_ok(element) {
  var here = $(element);
  here.closest("td").find(".minus").click();
  here.closest("td").find(".plus").click();
}


$(document).on("click", ".couponCopyBtn", function () {
  const selectedCode = $(this).attr("data-code");
  console.log("get coupon code", selectedCode);
  $(".coupon_code").val(selectedCode);
});

function checkAvailableCoupon() {
  var storeIds = [];
  $.get(base_url + "index.php/home/cart_products",function(data){
    var cartdata = JSON.parse(data)
console.log('cartdata',cartdata);
    cartdata.forEach(function(item){
      var key = {
        store_id: item.store_id,
        product_id: item.product_id
    };
    storeIds.push(key);
      
    });
         console.log('storeIds',storeIds);
  });
  $.get(base_url + "index.php/home/getAllCoupon", function (data) {
    if (data) {
      console.log("all coupon", JSON.parse(data));
      const allCoupons = JSON.parse(data);
      couponsAllowedToShow = allCoupons.filter(
        (item) => {
          const diff = moment(item['till']).fromNow();
          var storeType = JSON.parse(item.spec).store_type;
          var product = JSON.parse(item.spec).product;
            var couponData = {
              storeType:storeType,
              product:product
            };  
            console.log('couponData',couponData);
            var foundCoupon = storeIds.find(function(idObject) {
              return idObject.store_id === couponData.storeType && idObject.product_id === couponData.product;
          });
          if (!diff.includes('ago') && foundCoupon ) {
            console.log('storeType',storeType);
            return true;
          } else {
            false;
          }
        }
      );
      console.log("allowed coupons", couponsAllowedToShow);
      $("#availableCoupons").children().remove();
      if (couponsAllowedToShow.length > 0) {
        couponsAllowedToShow.forEach((element) => {
          // showing only active coupon code
          $("#availableCoupons").append(`
                      <li class="li list-group-item">
              <div class="row">
                  <div class="col-lg-8">
                  <button class="btn btn-warning" style="width: 100%;">${element["code"]}</button>
                  </div>
                  <div class="col-lg-4 text-right">
                      <span data-code="${element["code"]}" class="btn btn-danger couponCopyBtn">Avail</span>
                  </div>
                  <div class="text-left">
                  <span  class="" style="color:#11772d;margin-left:10px;">
                  Minimum order  <sup>RM</sup>${element["min_order_amount"]}
                  </span>
                  </div>
              </div>
              
          </li>
                      `);
        });
      }
    }
  });
  // $.get(base_url + "index.php/home/getAllCoupon", function (data) {
  //   if (data) {
  //     console.log("all coupon", JSON.parse(data));
  //     const allCoupons = JSON.parse(data);
  //     couponsAllowedToShow = allCoupons.filter(
  //       (item) => {
  //         const diff = moment(item['till']).fromNow();
  //         if (!diff.includes('ago')) {
  //           return true;
  //         } else {
  //           false;
  //         }
  //       }
  //     );
  //     console.log("allowed coupons", couponsAllowedToShow);
  //     $("#availableCoupons").children().remove();
  //     if (couponsAllowedToShow.length > 0) {
  //       couponsAllowedToShow.forEach((element) => {
  //         // showing only active coupon code
  //         $("#availableCoupons").append(`
  //                     <li class="li list-group-item">
  //             <div class="row">
  //                 <div class="col-lg-8">
  //                 <button class="btn btn-warning" style="width: 100%;">${element["code"]}</button>
  //                 </div>
  //                 <div class="col-lg-4 text-right">
  //                     <span data-code="${element["code"]}" class="btn btn-danger couponCopyBtn">Avail</span>
  //                 </div>
  //                 <div class="text-left">
  //                 <span  class="" style="color:#11772d;margin-left:10px;">
  //                 Minimum order  <sup>RM</sup>${element["min_order_amount"]}
  //                 </span>
  //                 </div>
  //             </div>
              
  //         </li>
  //                     `);
  //       });
  //     }
  //   }
  // });
}

$("body").on("click", ".quantity-button", function () {
  var here = $(this);
  var quantity = here.closest("td").find(".quantity_field").val();
  var limit = here.closest("td").find(".quantity_field").data("limit");
  if (here.val() == "minus") {
    quantity = quantity - 1;
  } else if (here.val() == "plus") {
    //if(limit == 'no'){
    quantity = Number(quantity) + 1;
    // }
  }
  if (quantity >= 1) {
    here.closest("td").find(".quantity_field").val(quantity);

    var rowid = here.closest("td").find(".quantity_field").data("rowid");
    var lim_t = here.closest("tr").find(".limit");
    var list1 = here.closest("tr").find(".sub_total");

    $.ajax({
      url:
        base_url +
        "index.php/home/cart/quantity_update/" +
        rowid +
        "/" +
        quantity,
      beforeSend: function () {
        list1.html("...");
      },
      success: function (data) {
        var res = data.split("---");
        console.log("quantity update", res);
        list1.html(res[0]).fadeIn();
        reload_header_cart();
        others_count();
        if (res[1] !== "not_limit") {
          lim_t.html("!!").fadeIn();
          here.closest("td").find(".plus").hide();
          here.closest("td").find(".quantity_field").data("limit", "yes");
          here.closest("td").find(".quantity_field").val(res[1]);
        } else {
          lim_t.html("").fadeOut();
          here.closest("td").find(".plus").show();
          here.closest("td").find(".quantity_field").data("limit", "no");
        }
        // show coupon based on the grand total
        checkAvailableCoupon();
      },
      error: function (e) {
        console.log(e);
      },
    });
  }
});

function cart_submission(elem) {
  $(".pay_gif").addClass("see");
  if (elem.hasAttribute("disabled") || elem.classList.contains("disabled")) {
    return;
  }

  //var payment_type = $('#ab').val();
  var payment_type = "";
  var state = check_login_stat("state");
  state.success(function (data) {
    var form = $("#cart_form");
    form.submit();
  });
}
/*function cart_submission(){
	
    $(".pay_gif").addClass("see");
	
    //var payment_type = $('#ab').val();
    var payment_type = '';
    var state = check_login_stat('state');
    state.success(function (data) {
        if(data == 'hypass'){
             var form = $('#cart_form');
             form.submit();
        } else {
            signin();
            $('#login_form').attr('action',base_url+'index.php/home/login/do_login/nlog');
            $('#logup_form').attr('action',base_url+'index.php/home/registration/add_info/nlog');
        }
    });
}*/

$(document).ready(function () {
  update_calc_cart();
  checkAvailableCoupon();
  $(".colrs").each(function () {
    var here = $(this);
    var rad = here.closest("li").find("input");
    if (rad.is(":checked")) {
      setTimeout(function () {
        here.click();
      }, 800);
    }
  });
});

function update_prices() {
  var url = base_url + "index.php/home/cart/calcs/prices";
  $.ajax({
    url: url,
    dataType: "json",
    beforeSend: function () { },
    success: function (data) {
      console.log("update price", data);
      $.each(data, function (key, item) {
        var elem = $("table").find("[data-rowid='" + item.id + "']");
        elem.find(".sub_total").html(item.subtotal);
        elem.find(".pric").html(item.price);
        console.log('item subtotal', item.subtotal);
      });
    },
    error: function (e) {
      console.log(e);
    },
  });
}

function update_calc_cart() {
  var url = base_url + "index.php/home/cart/calcs/full";
  var total = $("#total");
  var ship = $("#shipping");
  var tax = $("#tax");

  //  }
  var grand = $("#grand");
  var appendes1 = $(".box_shadow");
  var code = $('.coupon_code').val();
  var commonData = { code: code };  
  $.ajax({
    url: url,
    data: commonData,
    beforeSend: function () {
      total.html("...");
      ship.html("...");
      tax.html("...");
      grand.html("...");
    },
    success: function (data) {
      var res = data.split("-");
      var lastPart = res[res.length - 1];
    //   console.log('sgsgs'+lastPart+'rewer');
      if(lastPart){
        console.log('LPart--',lastPart);
        var currency = 'RM' + lastPart;
        $('#totalDiscountValue').text(currency);
        $('#total_dis').val(currency);
        }else{
          var noVal = 'RM0.00';
          $('#total_dis').val(noVal);
        }
      //alert(res);
      total.html(res[0]).fadeIn();
      ship.html(res[1]).fadeIn();
      tax.html(res[2]).fadeIn();
      {
        var d1=[];
        if($("#lalamove_res").val()!='')
        {
            d1=JSON.parse($("#lalamove_res").val());
        }
        var total1=parseFloat(total.html().toUpperCase().replace('RM', ''));
        var total_1=(!isNaN(total1))?total1:0.0;
        var tax1=parseFloat(tax.html().toUpperCase().replace('RM', ''));
        var tax_1=(!isNaN(tax1))?tax1:0.0;
        var disco1=parseFloat($("#total_dis").val().toUpperCase().replace('RM', ''));
        var disco_1=(!isNaN(disco1))?disco1:0.0;
        var delivery_1=0;
        for(let i=0; i<d1.length; i++){
            var d1_1=JSON.parse(d1[i]);
            if(!isNaN(d1_1['data']['priceBreakdown']['total']))
            {delivery_1+=parseFloat(d1_1['data']['priceBreakdown']['total']);}
        }
        var grand_1=(total_1+tax_1-disco_1+delivery_1);
        grand.html("RM"+(grand_1.toFixed(2))).fadeIn();
                
      }
    //   grand.html(res[3]).fadeIn();
      if (res[5] == 1) {
        if (parseFloat(res[0]) > 0 && parseFloat(res[0]) < parseFloat(res[6])) {
          $("#minim,#remove3").remove();

          $(appendes1).prepend(
            "<p id='minim'>Add $" +
            amount.toFixed(2) +
            " of items for FREE delivery!</p>"
          );
        }
      } else {
        $("#remove3,#minim").remove();
      }
      const discount = res[7];
      if (discount) {
        $(".coupon_disp").show();
        $("#disco").text(discount);
      }
      //other_action();
      payment_type_div_req();
    },
    error: function (e) {
      console.log(e);
    },
  });
}

$('body').on('click', '.coupon_btn', function () {
  var txt = $(this).html();
  var code = $('.coupon_code').val();
  $('#coup_frm').val(code);
  var form = $('#coupon_set');
  var subTotal = $('#total').text().split('RM')[1];
  var venID =  $('#venId').val();
  // console.log('total', subTotal);
  $.post(base_url + 'index.php/home/checkCouponIsValid', {
    code,
    subTotal,
    currstoreid: venID
  }, function (data) {
    console.log('data', data);
    if (data == 1) {
      // allowed to apply coupon
      var formdata = false;
      if (window.FormData) {
        formdata = new FormData(form[0]);
      }
      var datas = formdata ? formdata : form.serialize();
      $.ajax({
        url: base_url + 'index.php/home/coupon_check_backup/',
        type: 'POST', // form submit method get/post
        dataType: 'html', // request type html/json/xml
        data: datas, // serialize form data 
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
          $(this).html(applying);
        },
        success: function (result) {
          if (result == 'nope') {
            notify(coupon_not_valid, 'warning', 'bottom', 'right');
          } else {
            update_calc_cart();
            reload_header_cart();
            update_prices();
            var re = result.split(':-:-:');
            var ty = re[0];
            var ts = re[1];
            $("#coupon_report").fadeOut();
            if (ty == 'total' || ty == 'wise') {
              notify(coupon_discount_successful, 'success', 'bottom', 'right');
              $("#coupon_active").show();
              $("#coupon_inactive").hide();
              $(".add_coupon").hide();
              $("#shipping").html(re[2]);
              $("#coupon_active").html(ts);
              $(".remove-coupon").show();
              const discount = res[7];
              if (discount) {
                $(".coupon_disp").show();
                $("#disco").text(discount);
              }
            }
            else {
              $("#coupon_active").hide();
              $("#coupon_inactive").show();
              $(".remove-coupon").hide();
              $(".add_coupon").show();
            }
            $("#coupon_report").html('<h3>' + ts + '</h3>');
            $("#coupon_report").fadeIn();
          }
        }
      });
    } else if(data == 0){
      notify('Coupon Not Valid!!', 'warning', 'bottom', 'right');
    }
    else if(data == 2){
      notify('Must be more then minimum order amount!!', 'warning', 'bottom', 'right');
    }
    else if(data == 3){
      notify('Different Store Coupon!!', 'warning', 'bottom', 'right');
    }
    else {
      // not allowed to apply coupon
      notify('Error', 'warning', 'bottom', 'right');
    }
  });

});



function set_cart_map() {
  //$('#maps').animate({ height: '400px' }, 'easeInOutCubic', function(){});
  initialize();
  var address = [];
  //$('#pos').show('fast');
  //$('#lnlat').show('fast');
  $(".address").each(function (index, value) {
    if (this.value !== "") {
      address.push(this.value);
    }
  });
  address = address.toString();
  deleteMarkers();
  geocoder.geocode({ address: address }, function (results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      if ($("#langlat").val().indexOf(",") == -1 || $("#first").val() == "no") {
        deleteMarkers();
        var location = results[0].geometry.location;
        var marker = addMarker(location);
        map.setCenter(location);
        $("#langlat").val(location);
      } else if ($("#langlat").val().indexOf(",") >= 0) {
        deleteMarkers();
        var loca = $("#langlat").val();
        loca = loca.split(",");
        var lat = loca[0].replace("(", "");
        var lon = loca[1].replace(")", "");
        var marker = addMarker(new google.maps.LatLng(lat, lon));
        map.setCenter(new google.maps.LatLng(lat, lon));
      }
      if ($("#first").val() == "yes") {
        $("#first").val("no");
      }
      // Add dragging event listeners.
      google.maps.event.addListener(marker, "drag", function () {
        $("#langlat").val(marker.getPosition());
      });
    }
  });
}

var geocoder;
var map;
var markers = [];
function initialize() {
  geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(-34.397, 150.644);
  var mapOptions = {
    zoom: 14,
    center: latlng,
  };
  map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
  google.maps.event.addListener(map, "click", function (event) {
    deleteMarkers();
    var marker = addMarker(event.latLng);
    $("#langlat").val(event.latLng);
    // Add dragging event listeners.
    google.maps.event.addListener(marker, "drag", function () {
      $("#langlat").val(marker.getPosition());
    });
  });
}

/*
    var address = [];
    $('#maps').show('fast');
    $('#pos').show('fast');
    $('#lnlat').show('fast');
    $(".address").each(
    address.push(this.value);
    );
*/

$("body").on("blur", ".address", function () {
  if (!$(this).is("select")) {
    set_cart_map();
  }
});

$("body").on("change", ".address", function () {
  if ($(this).is("select")) {
    set_cart_map();
  }
});

// Add a marker to the map and push to the array.
function addMarker(location) {
  var image = {
    url: base_url + "uploads/others/marker.png",
    size: new google.maps.Size(40, 60),
    origin: new google.maps.Point(0, 0),
    anchor: new google.maps.Point(20, 62),
  };

  var shape = {
    coords: [1, 5, 15, 62, 62, 62, 15, 5, 1],
    type: "poly",
  };

  var marker = new google.maps.Marker({
    position: location,
    map: map,
    draggable: true,
    icon: image,
    shape: shape,
    animation: google.maps.Animation.DROP,
  });
  markers.push(marker);
  return marker;
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
  clearMarkers();
  markers = [];
}

// Sets the map on all markers in the array.
function setAllMap(map) {
  for (var i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
  setAllMap(null);
}
//google.maps.event.addDomListener(window, 'load', initialize);
