@extends('emails.layout.app')

@section('content')
	<tr>
      <td align="center" bgcolor="#e9ecef">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
          <tr>
            <td align="left" bgcolor="#ffffff" style="padding: 36px 24px 0; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;">
              <p>Hello {{$details['name']}},</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <!-- end hero -->

    <!-- start copy block -->
    <tr>
      <td align="center" bgcolor="#e9ecef">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
          <!-- start copy -->
          <tr>
            <td align="left" bgcolor="#ffffff" style=" word-break: break-word; padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif;">
                <p>As per yout forgot password request, here is the link at where you can reset password.</p>
                <a href="{{$details['link']}}">{{$details['link']}}</a>
            </td>
          </tr>
          <!-- end copy -->
        </table>
      </td>
    </tr>
@stop