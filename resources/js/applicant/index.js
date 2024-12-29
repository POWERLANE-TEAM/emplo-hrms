

import "../../css/applicant/dashboard.css";
import '../script.js';
import './applicant.js';
import addGlobalScrollListener, { documentScrollPosY } from 'global-scroll-script';
import addGlobalListener, { GlobalListener } from 'globalListener-script';
import createGradient from '../utils/color.js';
// import './livewire.js'





export function showContent(targetId, sectionId) {
  
    // Hide all content sections
    const contentSections = document.querySelectorAll('.content-section');
    contentSections.forEach((section) => section.classList.remove('active'));
  
    // Hide the default landing section
    const defaultLanding = document.getElementById('default-landing');
    if (defaultLanding) {
        defaultLanding.style.display = 'none'; // Hides the default content
    }
  
    const contentToShow = document.getElementById(targetId);
    if (contentToShow) {
        contentToShow.classList.add('active');
    } else {
        console.error('Content section not found for target: ' + targetId);
    }

    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach((item) => item.classList.remove('active'));
  
    const navItem = document.querySelector(`.nav-item[data-target="${targetId}"]`);
    if (navItem) {
        navItem.classList.add('active');
    } else {
        console.error('Nav item not found for target: ' + targetId);
    }
  
    const navSections = document.querySelectorAll('.nav-section');
    navSections.forEach((section) => section.classList.remove('active-header'));
  
    const activeSection = document.getElementById(sectionId);
    if (activeSection) {
        activeSection.classList.add('active-header');
    } else {
        console.error('Nav section not found for ID: ' + sectionId);
    }
}
  
window.showContent = showContent;



