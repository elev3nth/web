<section id="login" class="w-full">
  <form method="post" action="{{ host~'/'~admin~'/'~login }}">
    <div class="w-full mt-[1em] lg:mt-[2em] lg:w-[70%]">
      <div class="
      m-auto bg-main-logo bg-center bg-no-repeat bg-cover
      w-[6em] h-[6em] lg:w-[8em] lg:h-[8em]
      "></div>
    </div>
    <div class="
      bg-gray-100 m-auto min-h-[20em] border-slate-200
      h-auto w-[95%] items-center justify-center my-[1em]
      lg:border lg:border-solid
      lg:rounded-md lg:my-[2em] lg:flex lg:h-[35em] lg:w-[70%]
    ">
      <div class="
        bg-login-splash bg-center bg-no-repeat bg-cover
        w-full h-[20em] lg:h-full lg:rounded-tl-md lg:rounded-bl-md
      "></div>
      <div class="
        w-full h-full login-form
        lg:rounded-tr-md lg:rounded-br-md
      ">
        <h3 class="
          w-full p-0 pt-[1em] px-[1em]
          text-center text-red-400
          lg:text-[2em] lg:pt-[2em] lg:px-[3em]
        ">
          {{ get.msg ? get.msg : '&nbsp;' }}
        </h3>
        <div class="
          w-full pt-[2em] px-[1em] lg:pt-[4em] lg:px-[3em]
        ">
          <input type="email" id="userName" name="userName" class="
          border border-solid border-slate-200 rounded-md
          w-full p-2
          " required />
        </div>
        <div class="
          w-full pt-[2em] px-[1em] lg:pt-[2em] lg:px-[3em]
        ">
          <input type="password" id="userPass" name="userPass" class="
          border border-solid border-slate-200 rounded-md
          w-full p-2
          " required />
        </div>
        <div class="
          w-full flex pt-[1.5em] px-[0.5em] lg:pt-[2em] lg:px-[3em]
        ">
          <div class="
            text-left w-full text-base px-2 lg:text-lg lg:px-[2em]
          ">
            <label>
              <input type="checkbox" id="rememberMe" name="rememberMe"
              value="1" class="w-4 h-4 rounded" />&nbsp;&nbsp;Remember Me
            </label>
          </div>
          <div class="
            text-right w-full text-base px-2 lg:text-lg lg:px-[2em]
          ">
            <a href="{{ host~'/'~admin~'/'~forgotpassword }}" class="
              underline
            ">
              Forgot Password?
            </a>
          </div>
        </div>
        <div class="px-[3em] w-full p-[2em] lg:pt-[2em]">
          <input type="hidden" id="userCsrf" name="userCsrf"
          value="{{ csrf }}" />
          <button type="submit" id="userLogin" name="userLogin" class="
          border border-solid border-black rounded-md text-white
          font-bold w-full p-2 h-14 bg-gradient-to-r from-slate-600 to-black
          text-lg
          ">Submit</button>
        </div>
      </div>
    </div>
  </form>
</section>
