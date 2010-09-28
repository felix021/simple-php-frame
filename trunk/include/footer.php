<div id="foot-Pan">
    copyleft 2010 felix021 | Server time: <?php echo date("Y-m-d H:i:s"); ?>
</div>
<script> 
var trs = document.getElementsByTagName("tr");
for (var i = 0; i < trs.length; i++) {
    var tr = trs[i];
    tr.onmouseover = function () {
        this.color = this.style.background;
        this.style.background = "#bedcfa";
    }
    tr.onmouseout = function () {
        this.style.background = this.color;
    }
}
</script> 
</body>
</html>
