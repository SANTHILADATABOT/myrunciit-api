<section class="page-section color review">
   <div class="container" id="login">
      <div class="row margin-top-0">
         <div class="col-lg-10 col-lg-offset-1 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">
            <div class="_1Z9vji">
               <div class="NWW_bH"><span>Ratings &amp; Reviews</span></div>
               <div class="_2tw077">
                  <a href="javascript:void(0);">
                     <div class="_3wBtPi">
                        <div class="Sw6kZ2"><span><?php echo $this->crud_model->get_type_name_by_id('product', $product_id, 'title'); ?></span></div>
                        <div class="mBjPf6" style="visibility:hidden;">
                           <div class="niH0FQ _36Fcw_">
                              <span id="productRating_undefined_undefined_" class="_2_KrJI">
                                 <div class="hGSR34">3.8</div>
                              </span>
                              <span class="_38sUEc">(17,527)</span>
                           </div>
                        </div>
                     </div>
                     <div class="_2sxWXr" style="display:none;"><img src="https://rukminim1.flixcart.com/image/48/48/jyug0i80/dress/y/d/b/m-kn-cd-004-raghumaya-original-imaffzaebyntjkpm.jpeg?q=90" class="hoZMHD">
                     <img src="<?php echo $this->crud_model->file_view('product', $product_id,'','','thumb','src','multi','all'); ?>" class="hoZMHD"></div>
                  </a>
               </div>
            </div>
            <?php
               echo form_open(base_url() . 'index.php/home/reviews/add/'.$product_id.'/'.$sale_id, array(
                   'class' => 'form-login',
                   'method' => 'post',
                   'id' => ''
               ));
               
               ?>
            <div class="_2rfo2u">
               <div class="_3AwHsn">
                  <div class="_1O_Sj9"><span>Rate this product</span></div>
                  <div class="Kj7FZF">
                     <span class="star-rating star-5">
                     <input type="radio" name="rating" value="1"><i></i>
                     <input type="radio" name="rating" value="2"><i></i>
                     <input type="radio" name="rating" value="3"><i></i>
                     <input type="radio" name="rating" value="4"><i></i>
                     <input type="radio" name="rating" value="5"><i></i>
                     </span>
                  </div>
               </div>
               <hr class="_2qU1N7">
               <div class="_3zS_0H">
                  <div class="_1O_Sj9"><span>Review this product</span></div>
                  <div class="_2ynvot">
                     <div class="_32o8JY">
                        <div class="_1AyhZ5"><span>Description</span></div>
                        <textarea rows="8" placeholder="Description..." class="_16nh-W" name="description"></textarea>
                     </div>
                     <hr class="_2qU1N7">
                     <div class="_32o8JY">
                        <div class="_1AyhZ5"><span>Title (optional)</span></div>
                        <input value="" placeholder="Review title..." class="_16nh-W" name="title">
                     </div>
                  </div>
                  <span class="btn btn-theme-sm btn-block btn-theme-dark  review_btn enterer">
                                Submit Review                            
              </span>
               </div>
               
            </div>
            </form>
         </div>
      </div>
   </div>
</section>
