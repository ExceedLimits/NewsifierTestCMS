<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ $article->title }}</title>
</head>
<body style="padding: 1rem;">
  <h2 style="text-align:center;width:100%">
    {{ $article->title }}
 </h2>
 <div>      
  <div class="row">
    <div id="content">
        {{$article->content}}
    </div>
  </div>
</div>
<script>
  var blocks= JSON.parse(document.getElementById('content').innerHTML).blocks;
  var convertedHtml = "";
    blocks.map(block => {
      
      switch (block.type) {        
        case "paragraph":
          convertedHtml += "<p>"+block.data.text+"</p>";
          break;
        case "delimiter":
          convertedHtml += "<hr />";
          break;
        case "gif":
          var images = block.data.url.split(';'); 
          for (var i = 0; i < images.length; i++) {
              if (images[i]!='') {
                  convertedHtml += '<img class="img-fluid" src='+images[i]+' />';
              }
          }            
          break;
        default:
          console.log("Unknown block type", block.type);
          break;
      }
    });
    
    document.getElementById('content').innerHTML=convertedHtml;

  
</script>
</body>
</html>


  

