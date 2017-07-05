<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <base href="<?=$BASE;?>" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" />


    <title><?=$TITLE?></title>

    <meta name="description" content="<?=$DISCRIPTION?>" />
    <meta name="keywords" content="<?=$KEYWORDS?>" />
    <?php if($NOINDEX):?>
        <meta name="robots" content="noindex, nofollow" />
    <?php endif?>
    <?php if(!empty($CANONICAL)):?>
        <link rel="canonical" href="<?=$CANONICAL?>" />
    <?php endif?>
    <?=$HEAD;?>
    <?=$CSS?>
    <?=$JS;?>


</head>
<body>
<?=$ADMIN_TOOLS?>
    <div class="wrap header">
        <div id="header">
            <div class="row logo-social">
                <a href="index.html"  class="logo"></a>
                <div class="social">
                    <a class="fa fa-facebook" aria-hidden="true" href="#"></a>
                    <a class="fa fa-twitter" aria-hidden="true" href="#"></a>
                    <a class="fa fa-youtube" aria-hidden="true" href="#"></a>
                    <a class="fa fa-pinterest-p" aria-hidden="true" href="#"></a>
                    <a class="fa fa-instagram" aria-hidden="true" href="#"></a>


                </div>
            </div>
            <div class="row welcome-slide">
                <div class="welcome">
                    <h2>welcome to  surfhouse</h2>
                    <p>
                        The only online store you will ever need for all your windsurfing and kitesurfing and SUP needs
                    </p>
                </div>

                <div class="title-slide">
                    <h2>JP Funride<br /> 2014</h2>
                    <p>Super easy going freeride boards based on the X-Cite Ride shape concept with additional control and super easy jibing. </p>
                    <a href="#" class="buynow">BUY NOW</a>
                </div>
            </div>

        </div>
    </div>
    <div class="wrap main">
        <div id="main" class="row">
            <div class="sidebar">
                <div class="menu">
                    <h2>Menu</h2>
                    <?=Monstercms\Core\Module::get('Site')->menu(1, 'menu');?>
                </div>
                <div class="banner">
                    Now is open!
                </div>
            </div>
            <div class="article">
                <h2>New products</h2>
                <div class="products row">

                    <a href="#" class="product">
                        <div class="sticker hot"></div>
                        <div class="img"><img src="images/product.jpg" /></div>
                        <div class="row">
                            <div class="name">ushingham Lightning 2014</div>
                            <div class="price">
                                €.770.50
                                <div class="old-price">
                                    €.1,270.15
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="#" class="product">

                        <div class="img"><img src="images/product.jpg" /></div>
                        <div class="row">
                            <div class="name">ushingham Lightning 2014</div>
                            <div class="price">
                                €.7700.50

                            </div>
                        </div>
                    </a>

                    <a href="#" class="product">
                        <div class="sticker new"></div>
                        <div class="img"><img src="images/product.jpg" /></div>
                        <div class="row">
                            <div class="name">ushingham Lightning 2014</div>
                            <div class="price">
                                €.770.50
                                <div class="old-price">
                                    €.1,270.15
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="#" class="product">

                        <div class="img"><img src="images/product.jpg" /></div>
                        <div class="row">
                            <div class="name">ushingham Lightning 2014</div>
                            <div class="price">
                                €.770.50

                            </div>
                        </div>
                    </a>

                    <a href="#" class="product">
                        <div class="sticker hot"></div>
                        <div class="img"><img src="images/product.jpg" /></div>
                        <div class="row">
                            <div class="name">ushingham Lightning 2014</div>
                            <div class="price">
                                €.770.50
                                <div class="old-price">
                                    €.1,270.15
                                </div>
                            </div>
                        </div>
                    </a>


                    <a href="#" class="product">

                        <div class="img"><img src="images/product.jpg" /></div>
                        <div class="row">
                            <div class="name">ushingham Lightning 2014</div>
                            <div class="price">
                                €.770.50

                            </div>
                        </div>
                    </a>

                </div>

                <h2>Top products</h2>

                <div class="products row">

                    <a href="#" class="product">
                        <div class="sticker hot"></div>
                        <div class="img"><img src="images/product.jpg" /></div>
                        <div class="row">
                            <div class="name">ushingham Lightning 2014</div>
                            <div class="price">
                                €.770.50
                                <div class="old-price">
                                    €.1,270.15
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="#" class="product">

                        <div class="img"><img src="images/product.jpg" /></div>
                        <div class="row">
                            <div class="name">ushingham Lightning 2014</div>
                            <div class="price">
                                €.7700.50

                            </div>
                        </div>
                    </a>

                    <a href="#" class="product">
                        <div class="sticker new"></div>
                        <div class="img"><img src="images/product.jpg" /></div>
                        <div class="row">
                            <div class="name">ushingham Lightning 2014</div>
                            <div class="price">
                                €.770.50
                                <div class="old-price">
                                    €.1,270.15
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <h2>Sale products</h2>

                <div class="products row">

                    <a href="#" class="product">
                        <div class="sticker hot"></div>
                        <div class="img"><img src="images/product.jpg" /></div>
                        <div class="row">
                            <div class="name">ushingham Lightning 2014</div>
                            <div class="price">
                                €.770.50
                                <div class="old-price">
                                    €.1,270.15
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="#" class="product">

                        <div class="img"><img src="images/product.jpg" /></div>
                        <div class="row">
                            <div class="name">ushingham Lightning 2014</div>
                            <div class="price">
                                €.7700.50

                            </div>
                        </div>
                    </a>

                    <a href="#" class="product">
                        <div class="sticker new"></div>
                        <div class="img"><img src="images/product.jpg" /></div>
                        <div class="row">
                            <div class="name">ushingham Lightning 2014</div>
                            <div class="price">
                                €.770.50
                                <div class="old-price">
                                    €.1,270.15
                                </div>
                            </div>
                        </div>
                    </a>

                </div>
            </div>
        </div>

        <div id="brands" class="row">
            <a class="brand" href="#"><img src="images/adidas.jpg" alt="Adidas"  /></a>
            <a class="brand" href="#"><img src="images/adidas.jpg" alt="Adidas"  /></a>
            <a class="brand" href="#"><img src="images/adidas.jpg" alt="Adidas"  /></a>
            <a class="brand" href="#"><img src="images/adidas.jpg" alt="Adidas"  /></a>
            <a class="brand" href="#"><img src="images/adidas.jpg" alt="Adidas"  /></a>

        </div>


        <div id="instagram">
            <h2> <span class="fa fa-instagram"></span> Instagram feed: <span class="heshtag">#surfhouse</span></h2>
            <div class="row">

                <a href="#"><img src="images/livello-26.jpg" /></a>
                <a href="#"><img src="images/livello-27.jpg" /></a>
                <a href="#"><img src="images/livello-28.jpg" /></a>
                <a href="#"><img src="images/livello-29.jpg" /></a>
                <a href="#"><img src="images/livello-26.jpg" /></a>
                <a href="#"><img src="images/livello-30.jpg" /></a>

            </div>
        </div>



        <div id="social" class="row">
            <a href="#" class="fa fa-facebook"></a>
            <a href="#" class="fa fa-twitter"></a>
            <a href="#" class="fa fa-pinterest-p"></a>

        </div>
    </div>
    <div class="wrap footer">
        <div id="footer" class="row">
            <div class="category">
                <h2>Category</h2>
                <ul class="menu">
                    <li><a href="#">about us</a></li>
                    <li><a href="#">eshop</a></li>
                    <li><a href="#">Features</a></li>
                    <li><a href="#">new collections</a></li>
                    <li><a href="#">blog</a></li>
                    <li><a href="#">contact</a></li>
                </ul>
            </div>
            <div class="account">
                <h2>Our Account</h2>
                <ul class="menu">
                    <li><a href="#">Your Account</a></li>
                    <li><a href="#">Personal information</a></li>
                    <li><a href="#">Addresses</a></li>
                    <li><a href="#">Discount</a></li>
                    <li><a href="#">Orders history</a></li>
                    <li><a href="#">Addresses</a></li>
                    <li><a href="#">Search Terms</a></li>
                </ul>
            </div>
            <div class="support">
                <h2>Our Support</h2>
                <ul class="menu">
                    <li><a href="#">Site Map</a></li>
                    <li><a href="#">Search Terms</a></li>
                    <li><a href="#">Advanced Search</a></li>
                    <li><a href="#">Mobile</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Mobile</a></li>
                    <li><a href="#">Addresses</a></li>
                </ul>
            </div>
            <div class="newsletter">
                <h2>Newsletter</h2>

                <p>Join thousands of other people subscribe to our news</p>
                <form action="#">
                    <input type="email" placeholder="insert email" />
                    <input type="submit" value="Submit" />
                </form>
                <div class="payments row">
                    <img src="images/aex.png" />
                    <img src="images/discover.png" />
                    <img src="images/maestero.png" />
                    <img src="images/mastercard.png" />
                    <img src="images/paypal.png" />
                    <img src="images/visa_straight.png" />
                </div>
            </div>
            <div class="aboutus">
                <h2>About Us</h2>
                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>
                <p class="contacts">

                    <span class="label">Phone:</span>1-999-342-9876 <br />
                    <span class="label">e-mail:</span> <a href="mailto:info@surfhouse.com"> info@surfhouse.com</a> <br />

                </p>
            </div>

        </div>
    </div>
    <div class="wrap copyright">
        <div id="copyright" class="row">
            <div class="copy">
                &copy; 2014  SURFHOUSE. All rights reserved - Designed by theuncreativelab.com
            </div>
            <div class="social">
                <a class="fa fa-facebook" aria-hidden="true" href="#"></a>
                <a class="fa fa-twitter" aria-hidden="true" href="#"></a>
                <a class="fa fa-youtube" aria-hidden="true" href="#"></a>
                <a class="fa fa-pinterest-p" aria-hidden="true" href="#"></a>
                <a class="fa fa-instagram" aria-hidden="true" href="#"></a>
            </div>
        </div>
    </div>


</body>
</html>