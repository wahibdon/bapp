document.getElementById("about-contest").addEventListener('click', tabs);
document.getElementById("view-entries").addEventListener('click', tabs);
document.getElementById("view-entries").addEventListener('click', populateEntries);
document.getElementById("winners").addEventListener('click', tabs);
document.getElementById('fbchoice').addEventListener('click', loadPhotos, false);
function tabs(e){
  try{
    var lih = document.getElementById('lih');
    lih.parentNode.removeChild(lih);
  }catch(e){}
  var hidables = document.querySelectorAll('.hidable');
  hidables[0].style.display = hidables[1].style.display = "none";
  var nav = document.querySelectorAll('.nav');
  nav[0].style.borderBottom = nav[1].style.borderBottom = nav[2].style.borderBottom = "none";
  e.target.parentNode.style.borderBottom = "3px solid #4a8ed1"
  switch(e.target.id){
    case "about-contest":
      document.getElementById('about-section').style.display = "block";
      break;
    case "view-entries":
      document.getElementById('entries-section').style.display = "block";
  }
}
function populateEntries(e){
  if(e.target.once)
    return;
  var id_req = new XMLHttpRequest();
  id_req.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
      document.getElementById("about-section").style.display = "none";
      var ids = JSON.parse(this.response);
      var gdiv = document.getElementById("entries-section");
      for (var id = 0; id < ids.length; id++){
        var ixhr = new XMLHttpRequest();
        ixhr.onreadystatechange = function(){
          if(this.readyState == 4 && this.status == 200){
            var entry = JSON.parse(this.response);
            var idiv = document.createElement('div');
            var ip = document.createElement('p');
            idiv.appendChild(ip);
            ip.innerHTML = entry.first+"<br />"+entry.city+", "+entry.state;
            idiv.style.backgroundImage = "url('data:image;base64,"+entry.image+"')";
            idiv.entry = entry;
            idiv.className = "gImage";
            gdiv.appendChild(idiv)
            idiv.addEventListener('click', showLargeImage, false);
          }
          e.target.once = true;
        }
        ixhr.open('get', 'view_images.php?id='+ids[id]);
        ixhr.send(null);
      }
    }
  };
  id_req.open('get', 'view_images.php?ids');
  id_req.send(null);
}
function showLargeImage(e){
  var elem = e.target
  console.log(elem);
  if (elem.nodeName == 'P')
    elem = e.target.parentNode;
  console.log(elem);
  var lih = document.createElement('div');
  lih.id = "lih"
  var image = document.createElement("img");
  image.src = "data:image;base64,"+elem.entry.image;
  image.width = "790";
  image.style.cursor = "pointer";
  lih.appendChild(image);
  image.addEventListener("click", function(){
    lih.parentNode.removeChild(lih);
    document.getElementById("entries-section").style.display = "block";
  })
  document.getElementById("entries-section").style.display = "none";
  document.getElementById("entries-section").parentNode.appendChild(lih);
}

//Imported from old app
function removeClass (haystack, needle){
  var cHaystack = haystack;
  cHaystack = cHaystack.replace(" "+needle, "");
  if (cHaystack == haystack){
    cHaystack = cHaystack.replace(needle, "");
  }
  return cHaystack;
}
document.getElementById("new-entry").addEventListener('click', function(){
  FB.login(function(response) {
    if(response.status == 'connected'){
      document.getElementById('choose').style.display = "block";
      FB.api('/me', function(response) {
        console.log('Successful login for: ' + response.name);
        var choose = document.getElementById('choose');
        choose.first = response.first_name;
        choose.last = response.last_name;
        choose.fbid = response.id;
        var location = response.location.name.split(", ");
        choose.city = location[0];
        choose.state = location[1];
      });
    }
  }, {scope: 'public_profile,email,user_photos,user_location', 
    return_scopes: true
  });
})
document.getElementById('fileButton').addEventListener('change', function (){
  var choose = document.getElementById('choose');
  var data = new FormData();
  data.append("first",choose.first);
  data.append("last",choose.last);
  data.append("fbid",choose.fbid);
  data.append('city', choose.city);
  data.append('state', choose.state);
  var post_req = new XMLHttpRequest();
  post_req.onreadystatechange = function(){
    if (post_req.readyState == 4){
      document.getElementById("choose").style.display = "none";
      var e = new Object();
      e.target = document.getElementById('view-entries');
      tabs(e);
      populateEntries(e);
    }
  }
  data.append("file", this.files[0]);
  post_req.open('post', 'upload.php');
  post_req.send(data);
});
function loadPhotos (){
  FB.api('/me/photos?limit=100&type=uploaded', function(response){
    var loadedPhotos = document.getElementById('loadedPhotos');
    loadedPhotos.style.display = "block";
    var loadedPhotosBottom = loadedPhotos.offsetTop+loadedPhotos.offsetHeight;
    var img, eClass;
    for(var i = 0; i<response.data.length; i++){
      img = document.createElement('img');
      img.width = 150;
      img.height = 150;
      img.setAttribute('class', 'fbPhotos');
      loadedPhotos.appendChild(img);
      img.addEventListener('click', function(){
        var choose = document.getElementById('choose');
        var data = "first="+choose.first;
        data+="&last="+choose.last;
        data+="&fbid="+choose.fbid;
        data+='&city='+ choose.city;
        data+='&state='+ choose.state;
        var post_req = new XMLHttpRequest();
        post_req.onreadystatechange = function(){
          if (post_req.readyState == 4){
            loadedPhotos.style.display = "none";
            document.getElementById("choose").style.display = "none";
            var e = new Object();
            e.target = document.getElementById('view-entries');
            tabs(e);
            populateEntries(e);
          }
        }
        data+="&url="+ encodeURIComponent(this.src.split("//")[1]);
        post_req.open('post', 'upload.php');
        post_req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
        post_req.send(data);
      });
    }
    var imgs = document.querySelectorAll('.fbPhotos');
    for(i = 0; i<response.data.length; i++){
      if(imgs[i].offsetTop<loadedPhotosBottom){
        imgs[i].src = response.data[i].source;
      }else{
        eClass = imgs[i].getAttribute('class');
        imgs[i].setAttribute('class', eClass + " lazyLoad");
        imgs[i].dataSRC = response.data[i].source;
      }
      loadedPhotos.addEventListener('scroll', function(){
        var lazyLoads = document.querySelectorAll(".lazyLoad");
        for (var i = 0; i<lazyLoads.length; i++){
          eClass = lazyLoads[i].getAttribute('class');
          if (lazyLoads[i].offsetTop - loadedPhotos.scrollTop < loadedPhotosBottom){
            lazyLoads[i].src = lazyLoads[i].dataSRC;
            lazyLoads[i].setAttribute('class', removeClass(eClass, "lazyLoad"));
          }
        }
      });
    } 
  });
}