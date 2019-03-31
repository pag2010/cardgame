<form name="auth_login" onsubmit="return onSubmitClickOnLogin()" method="POST">
    Логин <input required name="login" type="text"><br>
    Пароль <input id="password" required name="password" type="password"><br>
    Не прикреплять к IP(не безопасно) <input type="checkbox" name="not_attach_ip"><br>
    <input name="submit" type="submit" value="Войти">
</form>