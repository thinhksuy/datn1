import React from 'react';
import ContactPage from '../components/contact/ContactPage';
import Footer from "../components/home/Footer";
import Header from "../components/home/Header";

const ContactPageWrapper = () => {
  return (
    <>
      <Header />
      <ContactPage />
      <Footer />
    </>
  );
};

export default ContactPageWrapper;
