<!DOCTYPE html>
<html>
@include('emails.includes.mailhead')
<body style="background-color: #e9ecef;">

  <!-- start preheader -->
  <div class="preheader" style="display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;">
    
  </div>
  <!-- end preheader -->

  <!-- start body -->
  <table border="0" cellpadding="0" cellspacing="0" width="100%">

    <!-- start logo -->
    @include('emails.includes.mailheader')
    <!-- end logo -->

    <!-- start hero -->
    <tr>
      <td align="center" bgcolor="#e9ecef">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
          <tr>
            <td align="left" bgcolor="#ffffff" style="padding: 36px 24px 0; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;">
              <h3>VidUnit</h3>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    @yield('content')
    <!-- end hero -->

    <!-- start copy block -->
    

    <!-- end copy block -->

    <!-- start footer -->
@include('emails.includes.mailfooter')
    <!-- end footer -->

  </table>
  <!-- end body -->

</body>
</html>