using System;
using System.Web.UI;
using System.Xml.Xsl;

namespace Drinks
{
    public partial class _Default : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            if (!IsPostBack)
            {
                string searchField = Request.QueryString["searchField"];
                string searchValue = Request.QueryString["searchValue"];

                if (string.IsNullOrEmpty(searchField))
                {
                    searchField = "";
                }

                if (string.IsNullOrEmpty(searchValue))
                {
                    searchValue = "";
                }

                XsltArgumentList args = new XsltArgumentList();
                args.AddParam("searchField", "", searchField);
                args.AddParam("searchValue", "", searchValue);

                DrinkOrdersXml.TransformArgumentList = args;
            }
        }
    }
}