<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        @if(isset($article))
            Edit Article
        @else 
            Add Article
        @endif        
      </h2>
  </x-slot>
  
  <div class="block max-w-7xl mx-auto px-4 pt-8 pb-2.5 bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 "> 
      <form action="{{ isset($article)? route('articles.update',$article->id):route('articles.store') }}" enctype="multipart/form-data" method="POST" name="add_article" id="add_article">
        {{ csrf_field() }} 
              @isset($article)
              @method('put')
              @endisset       
              <div class="mb-6">
                <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Title</label>
                <input value="@isset($article) {{ $article->title }} @endisset" type="title" id="title" name="title" class="w-full p-2 bg-gray-50 border border-gray-300 text-gray-900 text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-fulldark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="title" required>
                <span class="text-danger">{{ $errors->first('title') }}</span>
              </div>
              <div class="mb-6">
                <label for="content" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Content</label>
                <input value="@isset($article) {{ $article->content }} @endisset" type="hidden" name="content" id="content"/>
                <div class="container w-full p-2 bg-gray-50 border border-gray-300 text-gray-900 text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-fulldark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  <div id="editorjs"></div>
                </div>
              </div>
              <button type="submit" class="px-6 py-3 text-blue-100 no-underline bg-blue-500 rounded hover:bg-blue-600 hover:no-underline hover:text-blue-200">Submit</button>
            </form>
    </div>    
    <script>
   
  class GifImage {
    //Editor Tool UI
    static get toolbox() {
      return {
        title: 'GIF',
        icon: '<svg width="17" height="15" viewBox="0 0 336 276" xmlns="http://www.w3.org/2000/svg"><path d="M291 150V79c0-19-15-34-34-34H79c-19 0-34 15-34 34v42l67-44 81 72 56-29 42 30zm0 52l-43-30-56 30-81-67-66 39v23c0 19 15 34 34 34h178c17 0 31-13 34-29zM79 0h178c44 0 79 35 79 79v118c0 44-35 79-79 79H79c-44 0-79-35-79-79V79C0 35 35 0 79 0z"/></svg>'
      };
    }

    //Constructor to preserve Data State
    constructor({data}){
      this.data = data;
    }

    //render method
    render(){      
      //building serach view

      var m= document.createElement('div');
      var html= "<div id='GIFModal' style='width: 100%;height: 100%;overflow: auto;'>"+
                  "<div style='background-color: #fefefe;margin: auto;padding: 20px;border: 1px solid #888;width: 80%;'>"+                      
                    "<input placeholder='Search GIF..' type='text' class='filter' id='filter' name='filter' style='width:100%'>"+
                    "<div id='gallery' class='gallery' style='display:inline-flex;width:100%;margin:0.5rem'></div><br>"+
                    "<span class='insert' style='cursor:pointer;width:20%;margin:0.5rem;padding:0.5rem;background:black;color:white;'>Insert</span>"+                        
                  "</div>"+                    
                "</div>"+
                "<div id='output' class='output' style='width:100%'></div>";
      m.innerHTML=html;            

      //search text changing requesting new batch of GIFs
      m.getElementsByClassName("filter")[0].addEventListener("input", function(event){

       const getGIFs = () => {
        return axios.get("https://g.tenor.com/v1/search?q="+event.target.value+"&key=LIVDSRZULELA&limit=10")
            .then(response=>{
              var arr=response.data.results;
              m.getElementsByClassName("gallery")[0].innerHTML='';
              arr.forEach(el => {
                m.getElementsByClassName("gallery")[0].innerHTML+=("<img class='one' onclick='this.classList.toggle(\"selected\")' width='10%'' height='100px' src='"+el.media[0].gif.url+"'/>");
              });
            })
            .catch(error=>{
                return error;
            });
        };
        getGIFs();                
      });

      //insert button handling
      m.getElementsByClassName("insert")[0].onclick = function() {
        m.firstChild.style.display = "none";
        var g= m.getElementsByClassName("gallery")[0];
        var images = g.querySelectorAll("img.one"); 
        m.getElementsByClassName("output")[0].value='';
        for (var i = 0; i < images.length; i++) {
          if (images[i].classList.contains('selected')) {
            m.getElementsByClassName("output")[0].innerHTML+=("<img width='100%' src='"+images[i].src+"'/>")
          }
        } 
      }


      //saved data handling - edit mode
      if (this.data && this.data.url){      
        m.firstChild.style.display = "none";
        var g= m.getElementsByClassName("gallery")[0];
        var images = this.data.url.split(';'); 
        m.getElementsByClassName("output")[0].value='';
        for (var i = 0; i < images.length; i++) {
          if (images[i]!='') {
            m.getElementsByClassName("output")[0].innerHTML+=("<img width='100%' src='"+images[i]+"'/>")
          }
        }
      }
        
      return m;
    }

    //save method
    save(blockContent){
      //extract GIFs URLs in order to save them later.
      var urls='';
      Array.from(blockContent.getElementsByClassName("output")[0].children).forEach((child, index) => {
        urls+=child.src+";";
      });
      return {
        url: urls
      }
    }
  }

  //old data is saved on hidden content component 
  try{
    var saveddata=document.getElementById('content').value;
  }catch(e){saveddata='';}
  

  //Editor JS object init
  const editor = new EditorJS({
      holder: 'editorjs',
      tools: {
        gif: GifImage
      },
      data:JSON.parse(saveddata==''?'{}':saveddata)
    }
  );


  //using hidden content compment as temp content data container.
  document.getElementById('add_article').addEventListener('submit', (event)=>{
    editor.save().then((outputData) => {     
      document.getElementById('content').value=JSON.stringify(outputData);
    }).catch((error) => {
      event.preventDefault();
    });
  });

  </script>
  </x-app-layout>