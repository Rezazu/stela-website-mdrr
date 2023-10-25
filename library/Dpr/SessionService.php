<?php
class Dpr_SessionService
{
    function __construct()
    {
        $this->user = Zend_Auth::getInstance()->getIdentity();
        $this->penggunaService = new Dpr_PenggunaService();
    }

    // switch role
    /**
     * pindah role dengan input session key index peran,akan merubah key peran_aktif pada session
     * @param integer $index index dari array key session peran
     * @return boolean 
     */
    function switchTo($index)
    {
        try {
            // validate index peran
            if ($index > count($this->user->peran) - 1 || $index < 0) throw new Exception();

            // ubah peran_aktif sesuai index dari session peran
            $this->user->peran_aktif = $this->user->peran[$index];
            Zend_Auth::getInstance()->getStorage()->write($this->user);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // function update status user session
    public function updateStatusUser()
    {
        // negasi aja udah pasti false = 0 kok
        // kalo >0 pasti true,kalo di negasi pasti balik ke 0
        $this->user->status = !$this->user->status;
        // update session storage
        Zend_Auth::getInstance()->getStorage()->write($this->user);
    }
}
