<section id="login" class="w-full">
  <form method="post" action="">
    <div class="w-full mt-[2em] lg:w-[70%]">
      <div class="
      m-auto bg-main-logo bg-center bg-no-repeat bg-cover
      w-[8em] h-[8em]
      "></div>
    </div>
    <div class="
      bg-gray-100 m-auto min-h-[10em] border border-solid
      border-slate-200 rounded-md lg:my-[2em] lg:flex lg:items-center
      lg:justify-center lg:h-[35em] lg:w-[70%]
    ">
      <div class="
        bg-login-splash bg-center bg-no-repeat bg-cover
        w-full h-full rounded-tl-md rounded-bl-md
      "></div>
      <div class="
        w-full h-full rounded-tr-md rounded-br-md
        bg-gradient-to-r from-blue-300 to-pink-300
      ">
        <div class="pt-[8em] px-[3em] w-full">
          <input type="email" id="userName" name="userName" class="
          border border-solid border-slate-200 rounded-md
          w-full p-2
          " required />
        </div>
        <div class="pt-[2em] px-[3em] w-full">
          <input type="password" id="userPass" name="userPass" class="
          border border-solid border-slate-200 rounded-md
          w-full p-2
          " required />
        </div>
        <div class="pt-[2em] px-[3em] w-full flex">
          <div class="text-left w-full px-[2em] text-lg">
            <label>
              <input type="checkbox" id="rememberMe" name="rememberMe"
              value="1" class="w-4 h-4 rounded" />&nbsp;&nbsp;Remember Me
            </label>
          </div>
          <div class="text-right w-full px-[2em] text-lg">
            <a href="{{ host~admin~'/forgot-password' }}">Forgot Password?</a>
          </div>
        </div>
        <div class="pt-[2em] px-[3em] w-full">
          <button type="submit" id="userLogin" name="userLogin" class="
          border border-solid border-black rounded-md text-white
          text-lg font-bold
          w-full p-2 h-14 bg-gradient-to-r from-slate-600 to-black
          ">Submit</button>
        </div>
      </div>
    </div>
  </form>
</section>
