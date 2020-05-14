/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const articleRoutes = {
  path: '/article',
  component: Layout,
  redirect: '/article/index',
  name: 'Article',
  alwaysShow: true,
  meta: {
    title: 'Article',
    icon: 'edit',
    permissions: ['article'],
  },
  children: [
    {
      path: 'index',
      component: () => import('@/views/articles/List'),
      name: 'ArticleList',
      meta: {
        title: 'articleList',
        icon: 'list',
        permissions: ['article.article'],
      },
    },
    {
      path: 'create',
      component: () => import('@/views/articles/Create'),
      name: 'CreateArticle',
      meta: {
        title: 'createArticle',
        icon: 'edit',
        permissions: ['article.article.create'],
      },
    },
    {
      path: 'edit/:id(\\d+)',
      component: () => import('@/views/articles/Edit'),
      name: 'EditArticle',
      meta: {
        title: 'editArticle',
        noCache: true,
        permissions: ['article.article.edit'],
      },
      hidden: true,
    },
  ],
};

export default articleRoutes;
