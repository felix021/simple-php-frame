<div id="foot-Pan">
    copyleft 2010 felix021 | Server time: <?php echo date("Y-m-d H:i:s"); ?>
</div>
<script> 
var trs = document.getElementsByTagName("tr");
for (var i = 0; i < trs.length; i++) {
    var tr = trs[i];
    tr.onmouseover = function () {
        this.className += " mouseover";
    }
    tr.onmouseout = function () {
        this.className = this.className.replace(" mouseover", "");
    }
}
</script> 
</body>
</html>
