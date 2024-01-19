
<div style="width:100%;float:left;">
  <p>Enter Title<br />
    <label for="messageTitleInput"></label>
    <input type="text" name="messageTitleInput" id="messageTitleInput" />
  </p>
  <p>    Enter Message<br />
    <textarea name="messageBox" id="messageBox" cols="45" rows="3">Type greeting message</textarea>
  </p>
  <p>Enter Font Size<br />
    <label for="messageFontSize"></label>
    <input name="messageFontSize" type="text" id="messageFontSize" value="24" />
  </p>
  <p>Font Color<br />
    <label for="messageColor"></label>
    <input name="messageColor" type="text" id="messageColor" value="#FFFFFF" />
</p>
  <p>Box Width<br />
    <label for="messageboxWidth"></label>
    <input name="messageboxWidth" type="text" id="messageboxWidth" value="50%" />
  </p>
  <p>Text Shadow 
    <input type="checkbox" name="messageShadow" id="messageShadow" />
    <label for="messageShadow"></label>
<br>
    <input type="button" name="button" id="button" onclick="updateText()" value="Update">
  </p>
</div>