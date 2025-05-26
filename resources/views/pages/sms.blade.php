


<form method="POST" action="{{ route('sms.send')  }}" >
    @csrf<br><br>
    <label for="mobile">Mobile Number without Country code</label>
    <br>
    <input id="mobile" name="mobile" placeholder="Mobile Number" value="">
<br><br>
    <label for="description">Content</label>
    <br>
    <textarea id="description" name="description" placeholder="Message"></textarea>
    <br><br>
    <button type="submit" >Send</button>
</form>
