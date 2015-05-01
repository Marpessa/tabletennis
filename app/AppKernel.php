<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function init()
    {
        // Please read http://symfony.com/doc/2.0/book/installation.html#configuration-and-setup
        umask(0000);

        parent::init();
    }
    
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            //new JMS\AopBundle\JMSAopBundle(),
            //new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            //new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),

            // Fixtures
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),

            // Doctrine-extensions
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            // User
            new FOS\UserBundle\FOSUserBundle(),
            new Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),
            new Application\Sonata\UserBundle\ApplicationSonataUserBundle(),
            //new FOS\AdvancedEncoderBundle\FOSAdvancedEncoderBundle(),
            //
            // Menu
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            // Breadcrumbs
            new WhiteOctober\BreadcrumbsBundle\WhiteOctoberBreadcrumbsBundle(),
            // Forum
            new Herzult\Bundle\ForumBundle\HerzultForumBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),

            // Sonata Core
            new Sonata\CoreBundle\SonataCoreBundle(),
            // Admin
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Application\Sonata\AdminBundle\ApplicationSonataAdminBundle(),

            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\CacheBundle\SonataCacheBundle(),
            //new Sonata\jQueryBundle\SonatajQueryBundle(),
            // ORM Admin
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            //new SimpleThings\EntityAudit\SimpleThingsEntityAuditBundle(),
            // Media
            new Sonata\MediaBundle\SonataMediaBundle(),
            new Application\Sonata\MediaBundle\ApplicationSonataMediaBundle(),
            new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
            new Sonata\ClassificationBundle\SonataClassificationBundle(),
            new Application\Sonata\ClassificationBundle\ApplicationSonataClassificationBundle(),

            new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new Sonata\FormatterBundle\SonataFormatterBundle(),
            // News
            //new Sonata\NewsBundle\SonataNewsBundle(),
            //new Application\Sonata\NewsBundle\ApplicationSonataNewsBundle(),
            // i18n
            new Sonata\IntlBundle\SonataIntlBundle(),
            // Google Map
            new Ivory\GoogleMapBundle\IvoryGoogleMapBundle(),
            // Feed
            new Eko\FeedBundle\EkoFeedBundle(),
            // TinyMCE
            new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
            // Comment
            new Gregwar\CaptchaBundle\GregwarCaptchaBundle(),

            // Base
            new Base\CategoryBundle\BaseCategoryBundle(),
            new Base\PageBundle\BasePageBundle(),
            new Base\NewsBundle\BaseNewsBundle(),
            new Base\EventBundle\BaseEventBundle(),
            new Base\VideosBundle\BaseVideosBundle(),
            new Base\PicsBundle\BasePicsBundle(),
            new Base\ForumBundle\BaseForumBundle(),
            new Base\GoogleMapBundle\BaseGoogleMapBundle(),
            new Base\SiteMapBundle\BaseSiteMapBundle(),
            new Base\ContactBundle\BaseContactBundle(),
            new Base\CguBundle\BaseCguBundle(),
            new Base\SocialBundle\BaseSocialBundle(),
            new Base\AdBundle\BaseAdBundle(),
            new Base\GoogleAgendaBundle\BaseGoogleAgendaBundle(),
            new Base\CommentBundle\BaseCommentBundle(),

            // TableTennis
            new TableTennis\AnnouncementBundle\TableTennisAnnouncementBundle(),
            new TableTennis\ClubBundle\TableTennisClubBundle(),
            new TableTennis\FfttBundle\TableTennisFfttBundle(),
            new TableTennis\FfttFeedBundle\TableTennisFfttFeedBundle(),
            new TableTennis\HomePageFrontendBundle\TableTennisHomePageFrontendBundle(),
            new TableTennis\LicenseeBundle\TableTennisLicenseeBundle(),
            new TableTennis\MainBundle\TableTennisMainBundle(),
            new TableTennis\ScoringTableBundle\TableTennisScoringTableBundle(),
            new TableTennis\MatchTypeBundle\TableTennisMatchTypeBundle(),
            new TableTennis\PointsCalculationBundle\TableTennisPointsCalculationBundle(),
            new TableTennis\TeamBundle\TableTennisTeamBundle(),
            new TableTennis\PartnerBundle\TableTennisPartnerBundle(),
            new TableTennis\UsefulInformationBundle\TableTennisUsefulInformationBundle(),

            // Geolocalisation
            new Geolocalisation\CountryBundle\GeolocalisationCountryBundle(),
            new Geolocalisation\RegionBundle\GeolocalisationRegionBundle(),
            new Geolocalisation\DepartmentBundle\GeolocalisationDepartmentBundle(),
            new Geolocalisation\CityBundle\GeolocalisationCityBundle()
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
