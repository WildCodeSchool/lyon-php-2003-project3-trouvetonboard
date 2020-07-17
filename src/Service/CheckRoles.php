<?php


namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CheckRoles extends AbstractController
{
    public function check(Request $request, string $route)
    {
        $currentRoute = $request->attributes->get('_route');

        if ($currentRoute == $route) {
            $getUser = $this->getUser();
            $currentRoles = ($getUser)? $getUser->getRoles(): null;
            if (!in_array("ROLE_ADMIN", $currentRoles)) {
                $this->addFlash("danger", "Vous n'avez pas les accès à cette route");
                return true;
            }
        }
    }
}
