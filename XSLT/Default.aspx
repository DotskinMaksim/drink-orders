<%@ Page Title="Home Page" Language="C#" MasterPageFile="~/Site.Master" AutoEventWireup="true" CodeBehind="Default.aspx.cs" Inherits="Drinks._Default" %>

<asp:Content ID="Content1" ContentPlaceHolderID="MainContent" runat="server">
    <script src="script.js"></script>
    <link href="styles.css" rel="stylesheet" />

    <div>
        <asp:Xml ID="DrinkOrdersXml" runat="server"
            DocumentSource="~/drink_orders.xml"
            TransformSource="~/drink_orders.xslt" />
    </div>

</asp:Content>