<?php

namespace App\Menu;

use App\Controller\CongressController;
use App\Entity\Instrument;
use App\Entity\Jeopardy;
use App\Entity\Official;
use Survos\FieldBundle\Registry\EntityMetaRegistry;
use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Service\ContextService;
use Survos\TablerBundle\Traits\KnpMenuHelperInterface;
use Survos\TablerBundle\Traits\KnpMenuHelperTrait;
use Survos\MeiliBundle\Service\MeiliService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class AppMenu implements KnpMenuHelperInterface
{
    use KnpMenuHelperTrait;

    public function __construct(
        private ContextService                 $contextService,
        #[Autowire('%kernel.environment%')] protected string $env,
        private MeiliService $meiliService,
        private EntityMetaRegistry $entityRegistry,
        private ?AuthorizationCheckerInterface $security = null,
    )
    {
    }

    #[AsEventListener(event: MenuEvent::NAVBAR_MENU)]
    public function midNavbarMenu(MenuEvent $event): void
    {
        $menu = $event->getMenu();
        foreach (['app_homepage', 'meili_admin'] as $route)
        {
            $this->add($menu, $route); // label: u($route)->after('app_')
        }
        $this->add($menu, 'survos_workflow_entities', label: "*entities");
        // Per-entity dashboards (#[EntityMeta]-annotated classes). Replaces
        // the old direct-to-meili links — each entry now opens the
        // EntityDashboardController page (db count + meili + ux-search + browse).
        foreach ($this->entityRegistry->getBrowsable() as $descriptor) {
            $this->add(
                $menu,
                'survos_entity_dashboard',
                ['code' => $descriptor->code],
                label: $descriptor->label,
                icon: $descriptor->icon,
            );
        }

        // api-platform/meilisearch PR proof of concept (github.com/api-platform/core#8443):
        // five ways to search/browse the same Movie data, to show that InstantSearch,
        // api-grid, and a raw GetCollection call all sit on top of the same two
        // operations -- one Doctrine-backed, one backed by the new Meilisearch provider.
        $searchDemos = $this->addSubmenu($menu, 'Search Demos', icon: 'mdi:movie-search');
        $this->add($searchDemos, 'meili_insta', ['indexName' => 'meili_movie'], label: 'InstantSearch (Meilisearch)');
        $this->add($searchDemos, 'demo_movie_meili_grid', label: 'api-grid + Meilisearch provider');
        $this->add($searchDemos, 'survos_admin_browse', ['code' => 'app_movie'], label: 'api-grid + Doctrine provider');
        $this->add($searchDemos, label: ' ', dividerAppend: true);
        $this->add($searchDemos, uri: '/api/meilisearch/movies', label: 'raw GetCollection (Meilisearch)', external: true, icon: 'mdi:code-json');
        $this->add($searchDemos, uri: '/api/movies', label: 'raw GetCollection (Doctrine)', external: true, icon: 'mdi:code-json');

        if ($this->env === 'dev') {
            $this->add($menu, 'survos_commands', label: "Commands");
        }
        $submenu = $this->addSubmenu($menu, 'Flysystem');
        foreach (['flysystem_browse_default'] as $route) {
            $this->add($submenu, $route);
        }

        foreach ($this->contextService->getConfig()['app']['social'] ?? [] as $platform => $value) {
            $this->add($menu, uri: $value, label: $platform, external: true, icon: 'bi:' . $platform);
        }

//        foreach (['app_credit'] as $route) {
//            $this->add($menu, $route, label: u($route)->after('app_'));
//        }
        }

    public function lastNavbarMenu(MenuEvent $event): void
    {
        return;
//        <li class="nav-item">
//                        <a target="_blank" rel="noopener" class="nav-link"
//                           href="https://github.com/thomaspark/bootswatch/"><i class="bi bi-github"></i><span
//                                    class="d-lg-none ms-2">GitHub</span></a>
//                    </li>
//                    <li class="nav-item">
//                        <a target="_blank" rel="noopener" class="nav-link" href="https://twitter.com/bootswatch"><i
//                                    class="bi bi-twitter"></i><span class="d-lg-none ms-2">Twitter</span></a>
//                    </li>
//

        $menu = $event->getMenu();
        foreach ($this->contextService->getConfig()['app']['social'] ?? [] as $platform => $value) {
            $this->add($menu, uri: $value, label: $platform, external: true, icon: 'bi:' . $platform);
        }
        $this->add($menu, label: ' ', dividerAppend: true);

        if (0) {
            $nested = $this->addSubmenu($menu, 'github', icon: 'bi:github');
            $this->add($nested, label: 'repo', uri: $this->contextService->getConfig()['app']['social']['github']);
            $this->add($nested, label: 'issues', uri: $this->contextService->getConfig()['app']['social']['github'] . '/issues');
        }
    }

    private function isDev(): bool
    {
        return $this->env === 'dev';
    }

    public function startNavbarMenu(MenuEvent $event): void
    {
        return;
        $menu = $event->getMenu();


        $this->add($menu, 'app_homepage', label: "Home");
        // app_simple?

        $this->add($menu, 'api_doc', label: 'API', external: true);
        // for nested menus, don't add a route, just a label, then use it for the argument to addMenuItem

        foreach ([CongressController::class,
//                     TermCrudController::class
                 ] as $controllerClass) {
            $controllerMenu = $this->addSubmenu($menu,
                label: (new \ReflectionClass($controllerClass))->getShortName());
            foreach (['simple_datatables',
//                         'index',
//                         'crud_index'
                     ] as $controllerRoute) {
                $this->add($menu, $controllerClass . '::' . $controllerRoute,
                    label: $controllerRoute);
            }

        }
    }

    public function pageMenu(MenuEvent $event): void
    {
    }

    #[AsEventListener(event: MenuEvent::FOOTER)]
    public function footerMenu(MenuEvent $event): void
    {
        $menu = $event->getMenu();

        foreach (['app_homepage'] as $route) {
            $this->add($menu, $route);
        }
        return;
        $nestedMenu = $this->addSubmenu($menu, 'Credits');
        foreach (['bundles', 'javascript'] as $type) {
            // $this->addMenuItem($nestedMenu, ['route' => 'survos_base_credits', 'rp' => ['type' => $type], 'label' => ucfirst($type)]);
            $this->addMenuItem($nestedMenu, ['uri' => "#$type", 'label' => ucfirst($type)]);
        }

    }

    public function sidebarMenu(MenuEvent $event): void
    {
    }
}
