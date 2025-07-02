    <div class="site-footer">
        <div class="container">
            {{-- <div class="row">
                <div class="col-lg-4">
                    <div class="widget">
                        <h3>Contact</h3>
                        <address>43 Raymouth Rd. Baltemoer, London 3910</address>
                        <ul class="list-unstyled links">
                            <li><a href="tel://11234567890">+1(123)-456-7890</a></li>
                            <li><a href="tel://11234567890">+1(123)-456-7890</a></li>
                            <li>
                                <a href="mailto:info@mydomain.com">info@mydomain.com</a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.widget -->
                </div>
                <!-- /.col-lg-4 -->
            </div> --}}
            <!-- /.row -->

            <div class="row">
                <div class="col-12 text-center">
                    <!--
              **==========
              NOTE:
              Please don't remove this copyright link unless you buy the license here https://untree.co/license/
              **==========
            -->

                    <p>
                        Copyright &copy;
                        <script>
                            document.write(new Date().getFullYear());
                        </script>
                        . All Rights Reserved &mdash; Designed with love by
                        My Home
                    </p>
                    <div>
                        Distributed by
                        <a href="https://github.com/Alfawakhry2" class="text-decoration-none fw-bold " target="_blank">Eng/Ahmed Alfawakhry </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container -->
    </div>
    <!-- /.site-footer -->

    <!-- Preloader -->
    <div id="overlayer"></div>
    <div class="loader">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <script src="{{asset('front/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('front/js/tiny-slider.js')}}"></script>
    <script src="{{asset('front/js/aos.js')}}"></script>
    <script src="{{asset('front/js/navbar.js')}}"></script>
    <script src="{{asset('front/js/counter.js')}}"></script>
    <script src="{{asset('front/js/custom.js')}}"></script>

    {{-- this used when need to add custom Script to only page --}}
    @stack('scripts')

</body>

</html>
