<footer class="main-footer">
    <strong>Copyright &copy; 2021- {{now()->year}} <a target="_blank" href="https://honghafeed.com.vn">Công ty cổ phần dinh dưỡng Hồng Hà</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <p id="time"></p>
    </div>
</footer>


<script src="{{ asset('plugins/jquery/jquery-3.4.0.js') }}"></script>
<script type="text/javascript">
var timestamp = '<?=time();?>';
function updateTime(){
  $('#time').html(Date(timestamp));
  timestamp++;
}
$(function(){
  setInterval(updateTime, 1000);
});
</script>
